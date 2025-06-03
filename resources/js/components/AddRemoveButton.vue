<script setup lang="ts">
defineProps({
    class: String,
});
</script>

<template>
    <div class="m-2 flex items-center justify-center rounded px-4 py-2 text-white" :class="class">
        <slot></slot>
    </div>
</template>
