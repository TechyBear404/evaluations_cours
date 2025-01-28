<template>
    <div>
        <h1>{{ form.name }}</h1>
    </div>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";

const props = defineProps({
    form: Object,
});
</script>
