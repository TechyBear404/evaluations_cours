<template>
    <Head title="Déja répondu" />
    <div
        class="flex flex-col items-center justify-center min-h-screen p-4 bg-gray-50"
    >
        <div class="w-full max-w-md space-y-8 text-center">
            <!-- Info Icon -->
            <div class="mb-8 text-6xl text-blue-500 animate-bounce">
                <font-awesome-icon icon="fa-solid fa-circle-info" />
            </div>

            <!-- Message principal -->
            <h1 class="mb-4 text-3xl font-bold text-gray-900">
                Vous avez déjà répondu à ce questionnaire
            </h1>
            <p class="mb-8 text-gray-600">
                Il n'est possible de répondre qu'une seule fois au
                questionnaire. Merci de votre participation!
            </p>
        </div>
    </div>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
</script>
