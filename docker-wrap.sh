#!/bin/bash
set -e

# Colors for output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

echo -e "${YELLOW}=== Restulator Local Release Builder ===${NC}"

# Determine version
CURRENT_TAG=$(git describe --tags --abbrev=0 2>/dev/null || echo "v0.0.0")
NEXT_VERSION=$(echo $CURRENT_TAG | awk -F. '{print $1"."$2"."$3+1}')

echo -e "${GREEN}Current tag: ${CURRENT_TAG}${NC}"
echo -e "${GREEN}Next version will be: ${NEXT_VERSION}${NC}"

# Create Docker image for building the release
echo -e "${YELLOW}Creating Docker build environment...${NC}"

cat > Dockerfile.build << 'EOF'
FROM php:8.2-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    rsync

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Node.js 20
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs \
    && node --version \
    && npm --version

WORKDIR /app

CMD ["/bin/bash", "-c", "/app/docker-build-script.sh"]
EOF

# Create build script that will run inside Docker
cat > docker-build-script.sh << 'EOF'
#!/bin/bash
set -e

GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

VERSION=${1:-"v0.1.0"}
echo -e "${YELLOW}Building release ${VERSION}${NC}"

# Install Composer dependencies
echo -e "${GREEN}Installing Composer dependencies...${NC}"
composer install --optimize-autoloader --no-progress --no-interaction --prefer-dist

# Install NPM dependencies and build assets
echo -e "${GREEN}Installing NPM dependencies...${NC}"
# Clean npm cache and remove existing node_modules
rm -rf node_modules package-lock.json
npm cache clean --force
# Install with platform override for rollup
npm install

echo -e "${GREEN}Building assets...${NC}"
# Set platform explicitly when building
NODE_OPTIONS="--max-old-space-size=4096" npm run build

# Prepare the release directory
echo -e "${GREEN}Preparing release files...${NC}"
mkdir -p /release
rsync -av --progress \
  --exclude=node_modules \
  --exclude=.git \
  --exclude=.github \
  --exclude=tests \
  --exclude=storage/**\* \
  --exclude=Dockerfile.build \
  --exclude=docker-build-script.sh \
  --exclude=Writerside \
  --exclude=.idea \
  ./ /release/

# Create storage directories
find storage -type d -exec mkdir -p /release/{} \;

# Create .env file from example
cp /release/.env.example /release/.env

# Generate application key
cd /release
php artisan k:g

# Create the ZIP file
echo -e "${GREEN}Creating ZIP archive...${NC}"
cd /release
zip -r /app/restulator-${VERSION}.zip .

echo -e "${GREEN}Release ZIP created at restulator-${VERSION}.zip${NC}"
EOF

chmod +x docker-build-script.sh

# Build Docker image with platform specification
echo -e "${YELLOW}Building Docker image...${NC}"
docker build --platform=linux/amd64 -t restulator-builder -f Dockerfile.build .

# Run container to build the release
echo -e "${YELLOW}Building release in Docker container...${NC}"
docker run --platform=linux/amd64 --rm -v "$(pwd):/app" restulator-builder /app/docker-build-script.sh $NEXT_VERSION

# Cleanup
echo -e "${GREEN}Cleaning up build files...${NC}"
rm Dockerfile.build docker-build-script.sh

echo -e "${YELLOW}=== Build Complete ===${NC}"
echo -e "${GREEN}Release file created: restulator-${NEXT_VERSION}.zip${NC}"
