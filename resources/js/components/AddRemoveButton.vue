<script setup lang="ts">
defineProps({
    classes: String,
});
</script>

<template>
    <div class="m-2 flex items-center justify-center rounded px-4 py-2 text-white" :class="classes">
        <slot></slot>
    </div>
</template>
