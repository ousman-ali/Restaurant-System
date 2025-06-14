<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Page builder</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/grapesjs/0.22.5/css/grapes.min.css"
          integrity="sha512-gHvsfu4IySlJhEgWA/xJJ2W4SM8sP4qKhbcHA/fnEwrzofYy2PTHN4iPfg8xm6BBa1iT6Uyb6bCHuJ5HA2KJWA=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>

    <style>
        * {
            margin: 0;
            padding: 0;
        }

        .page-builder-header {
            background-color: #292F36;
            color: white;
            padding: 15px 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .header-center h4 {
            margin: 0;
            font-weight: 600;
            color: white;
            font-size: 18px;
        }

        .header-center span {
            color: #FFE66D;
            font-weight: bold;
        }

        .back-link {
            color: #4ECDC4;
            text-decoration: none;
            font-weight: 500;
            padding: 8px 12px;
            border-radius: 4px;
            background-color: rgba(78, 205, 196, 0.1);
        }

        .back-link:hover {
            background-color: rgba(78, 205, 196, 0.2);
        }

        .save-btn {
            background-color: #FF6B6B;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            font-weight: 500;
            cursor: pointer;
        }

        .save-btn:hover {
            background-color: #ff5252;
        }

        @media (max-width: 600px) {
            .page-builder-header {
                flex-direction: column;
                gap: 10px;
                padding: 10px;
            }

            .header-left, .header-center, .header-right {
                width: 100%;
                text-align: center;
                margin: 5px 0;
            }
        }
    </style>

</head>
<body>
<div class="page-builder-header">
    <div class="header-left">
        <a href="{{url('/website')}}" class="back-link">
            ← Back To Dashboard
        </a>
    </div>
    <div class="header-center">
        <h4>Editing: <span>{{ $pageSection->name }}</span></h4>
    </div>
    <div class="header-right">
        <button id="save-btn" class="save-btn">
            Save Changes
        </button>
    </div>
</div>
<div id="gjs"></div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/grapesjs/0.22.5/grapes.min.js"
        integrity="sha512-qO/N7nSdIqXxVG/colwmEwCBf8e7dGKiwbUNLkjDq2Q/x8gRVhhLvqY19enVsd5m/5malEZUG2J2WKY93uTByg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://unpkg.com/grapesjs-blocks-basic"></script>


<script type="text/javascript">
    let currentSectionId = '{{ $pageSection->id }}';
    const editor = grapesjs.init({
        container: '#gjs',
        plugins: ["gjs-blocks-basic"],
        pluginsOpts: {
            'gjs-blocks-basic': {}
        },
        canvas: {
            styles: [
                'https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css',
                'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css'
            ],
            scripts: [
                'https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js'
            ]
        },
        storageManager: {
            type: 'local',
            autosave: true,
            autoload: false,
            stepsBeforeSave: 1,
            options: {
                local: {
                    key: `gjs-${currentSectionId}` // Use section ID in the storage key
                }
            }
        },
        components: {!! json_encode($pageSection->content) !!},
        style: `
        :root {
            --primary-color: #FF6B6B;
            --secondary-color: #4ECDC4;
            --accent-color: #FFE66D;
            --dark-color: #292F36;
            --light-color: #F7FFF7;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--dark-color);
            background-color: var(--light-color);
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: #ff5252;
            border-color: #ff5252;
        }

        .btn-secondary {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        .bg-custom-primary {
            background-color: var(--primary-color);
            color: white;
        }

        .bg-custom-secondary {
            background-color: var(--secondary-color);
            color: white;
        }

        .bg-custom-accent {
            background-color: var(--accent-color);
            color: var(--dark-color);
        }

        .bg-custom-dark {
            background-color: var(--dark-color);
            color: white;
        }

        .text-custom-primary {
            color: var(--primary-color);
        }

        .text-custom-secondary {
            color: var(--secondary-color);
        }

        .hero {
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('/api/placeholder/1920/600');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 150px 0;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(255, 107, 107, 0.7), rgba(78, 205, 196, 0.7));
            z-index: -1;
        }

        .menu-card {
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            border: none;
            margin-bottom: 30px;
        }

        .menu-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
        }

        .menu-img {
            height: 200px;
            object-fit: cover;
        }

        .menu-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: bold;
        }

        .navbar {
            padding: 15px 0;
            transition: all 0.3s;
        }

        .navbar-brand {
            font-size: 28px;
            font-weight: bold;
        }

        .nav-link {
            font-size: 16px;
            font-weight: 600;
            margin: 0 10px;
            position: relative;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background-color: var(--accent-color);
            transition: width 0.3s;
        }

        .nav-link:hover::after {
            width: 100%;
        }

        .section-title {
            position: relative;
            padding-bottom: 15px;
            margin-bottom: 40px;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 100px;
            height: 3px;
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            transform: translateX(-50%);
        }

        .portion-options {
            width: 100%;
        }

        .portion-option {
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .portion-option:hover {
            background-color: var(--accent-color) !important;
            border-color: var(--secondary-color) !important;
            transform: translateX(5px);
        }

        .portion-option .badge {
            font-size: 0.9rem;
            padding: 6px 10px;
        }

        .footer {
            background-color: var(--dark-color);
            color: white;
            padding: 60px 0 30px;
        }

        .social-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.1);
            margin-right: 10px;
            transition: all 0.3s;
        }

        .social-icon:hover {
            background-color: var(--primary-color);
            transform: translateY(-5px);
        }

        .category-tab {
            padding: 10px 20px;
            border-radius: 25px;
            margin: 0 5px 15px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .category-tab.active {
            background-color: var(--primary-color);
            color: white;
        }

        .dish-info {
            border-left: 3px solid var(--primary-color);
            padding-left: 15px;
        }

        .chef-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            background-color: var(--accent-color);
            color: var(--dark-color);
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
        }

        .about-img {
            border-radius: 20px;
            box-shadow: 20px 20px 0px var(--primary-color);
        }
        `,
    });

    editor.BlockManager.add('readonly-block', {
        label: 'Menus',
        media: `<span style="font-size: 2rem">🍽️</span>`,
        content: `<section id="menus"> @menus [do not edit this section, dish menus will be appear here in the website] </section>`,

    });

    editor.Components.addType('readonly-block', {
        model: {
            defaults: {
                tagName: 'div',
                classes: ['readonly-block'],
                draggable: false,  // Prevent dragging
                removable: false,  // Prevent deleting
                copyable: false,   // Prevent copying
                editable: false,   // Prevent text editing
                highlightable: false, // Prevent hover highlight
                resizable: false,  // Disable resizing
                traits: [], // Remove any editable traits
                attributes: {
                    'data-readonly': 'true', // Custom attribute
                },
            }
        }
    });


    // editor.DomComponents.addTypes('menu-section', {
    //     model: {
    //         defaults: {
    //             tagName: 'section',
    //             attributes: { id: 'menus' },
    //             draggable: true,
    //             droppable: false,
    //             selectable: true,
    //             highlightable: true,
    //             hoverable: true,
    //             editable: false,
    //             content: `<div class="menu-section-placeholder">
    //                       <h3>Menu Section</h3>
    //                       <p>This block will be replaced with <code>&lt;section id="menus"&gt;&lt;/section&gt;</code> when saved</p>
    //                       </div>`,
    //             traits: [],
    //         },
    //         init() {
    //             // Prevent modification of this component
    //             this.on('change:content', this.onContentChange);
    //         },
    //         onContentChange() {
    //             // Reset content if it's changed
    //             const defaultContent = `<div class="menu-section-placeholder">
    //                                   <h3>Menu Section</h3>
    //                                   <p>This block will be replaced with <code>&lt;section id="menus"&gt;&lt;/section&gt;</code> when saved</p>
    //                                   </div>`;
    //             this.set('content', defaultContent);
    //         }
    //     },
    //     view: {
    //         events: {
    //             dblclick: 'onDblClick'
    //         },
    //         onDblClick(e) {
    //             e.preventDefault();
    //             alert('This is a special menu section block. It will be saved as <section id="menus"></section> and cannot be edited.');
    //             return false;
    //         }
    //     }
    // })
    //
    // editor.BlockManager.addCategory({ id: 'custom-blocks', label: 'Special Blocks' });
    //
    // // Add the menu section block
    // editor.BlockManager.add('menu-section-block', {
    //     label: 'Menu Section',
    //     category: 'custom-blocks',
    //     attributes: { class: 'gjs-block-menu-placeholder' },
    //     content: { type: 'menu-section' },
    // });

    document.getElementById('save-btn').addEventListener('click', function () {
        const html = editor.getHtml();
        const css = editor.getCss();

        fetch('{{ route("website.save-section", $pageSection->id) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                content: html,
                styles: css
            })
        }).then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Section saved successfully!');

                    localStorage.setItem(`gjs-${currentSectionId}`, JSON.stringify({
                        html: html,
                        css: css,
                        sectionId: currentSectionId
                    }));

                } else {
                    alert('Error saving section: ' + (data.message || 'Unknown error'));
                }
            }).catch(error => {
            console.error('Error:', error);
            alert('Error saving section. Please try again.');
        });
    })


</script>


</body>
</html>
