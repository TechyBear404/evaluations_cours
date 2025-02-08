<template>
    <div
        class="flex flex-col items-center justify-center min-h-screen p-4 bg-gray-50"
    >
        <div class="w-full max-w-md space-y-8 text-center">
            <!-- Error Icon -->
            <div class="mb-8 text-6xl text-red-500 animate-bounce">
                <font-awesome-icon icon="fa-solid fa-circle-xmark" />
            </div>

            <!-- Message principal -->
            <h1 class="mb-4 text-3xl font-bold text-gray-900">
                Page non trouvée
            </h1>
            <p class="mb-8 text-gray-600">
                Désolé, le lien que vous avez utilisé n'existe pas ou n'est plus
                valide.
            </p>
        </div>
    </div>
</template>

<script setup></script>
