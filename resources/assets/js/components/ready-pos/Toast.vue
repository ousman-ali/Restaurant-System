<template>
    <div
        v-if="isToastRVissible"
        class="toast-notification"
        :class="{ 'show': isToastRVissible }"
    >
        {{ toastRMessage }}
    </div>
</template>

<script setup>
import useStore from './useStore';

const {isToastRVissible, toastRMessage} = useStore();
console.log('log from toast', isToastRVissible, toastRMessage);
</script>

<style scoped>
.toast-notification {
    position: fixed;
    top: 60px;
    left: 50%;
    transform: translateX(-50%) translateY(20px);
    background-color: #3498db;
    color: white;
    padding: 12px 20px;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    font-weight: 500;
    z-index: 1000;
    font-size: 14px;
    min-width: 200px;
    max-width: 300px;
    opacity: 0;
    text-align: center;
    transition: opacity 0.3s, transform 0.3s;
}

.toast-notification.show {
    opacity: 1;
    transform: translateX(-50%) translateY(0);
}

@media (max-width: 767px) {
    .toast-notification {
        min-width: 80%;
        max-width: 90%;
    }
}
</style>
