<template>
    <AppLayout>
        <div v-for="form in forms" :key="form.id">
            <h1>{{ form.name }}</h1>
            <Link :href="`/forms/${form.id}`">voir</Link>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    forms: Object,
});
</script>
