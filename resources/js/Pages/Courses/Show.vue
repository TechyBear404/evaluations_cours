<template>
    <AppLayout>
        <h1>{{ course.name }}</h1>
    </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";

const props = defineProps({
    course: {
        type: Object,
    },
});
</script>
