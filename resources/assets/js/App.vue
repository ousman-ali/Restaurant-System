<template>
    <div>
        <component :is="currentComponent"></component>
    </div>
</template>

<script setup>
import {ref, onMounted} from 'vue';
import Example from './components/Example.vue';  // Import Example component
import LiveOrder from './components/LiveOrder.vue';  // Import other component
import POS from './components/pos/Index.vue';

// Define available components
const components = {
    example: Example,
    liveOrder: LiveOrder,
    pos: POS,
};

// Create a ref to hold the dynamically chosen component
const currentComponent = ref(null);

onMounted(() => {
    // Set the component based on the global variable window.componentName
    currentComponent.value = components[window.componentName] || Example;  // Default to Example
});
</script>
