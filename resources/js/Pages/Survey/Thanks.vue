<template>
    <Head title="Merci" />
    <div
        class="flex flex-col items-center justify-center min-h-screen p-4 bg-gray-50"
    >
        <div class="w-full max-w-md space-y-8 text-center">
            <!-- Success Icon -->
            <div class="mb-8 text-6xl text-green-500 animate-bounce">
                <font-awesome-icon icon="fa-solid fa-circle-check" />
            </div>

            <!-- Message principal -->
            <h1 class="mb-4 text-3xl font-bold text-gray-900">
                Merci pour votre participation !
            </h1>
            <p class="mb-8 text-gray-600">
                Vos réponses ont été enregistrées avec succès. Votre feedback
                est précieux et nous aidera à améliorer nos services.
            </p>
        </div>
    </div>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
</script>
