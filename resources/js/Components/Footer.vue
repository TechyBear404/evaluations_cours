<template>
    <footer class="py-6 mt-auto border-t bg-primary/5 border-primary/10">
        <div class="container px-6 mx-auto">
            <div class="flex flex-col items-center justify-center space-y-4">
                <div class="flex items-center space-x-2 text-primary">
                    <font-awesome-icon
                        icon="fa-solid fa-graduation-cap"
                        class="text-xl"
                    />
                    <span class="text-xl font-semibold">IfoForm</span>
                </div>

                <div
                    class="flex items-center space-x-4 text-sm text-muted-foreground"
                >
                    <a
                        href="mailto:hadrien.janssens7@gmail.com"
                        class="flex items-center space-x-1 transition-colors duration-200 hover:text-primary"
                    >
                        <font-awesome-icon icon="fa-solid fa-envelope" />
                        <span>Hadrien</span>
                    </a>
                    <span>•</span>
                    <a
                        href="mailto:tekybear404@gmail.com"
                        class="flex items-center space-x-1 transition-colors duration-200 hover:text-primary"
                    >
                        <font-awesome-icon icon="fa-solid fa-envelope" />
                        <span>Sébastien</span>
                    </a>
                </div>

                <div class="text-sm text-muted-foreground">
                    <p>&copy; 2025 IfoForm. Tous droits réservés.</p>
                </div>
            </div>
        </div>
    </footer>
</template>

<script setup></script>

<style lang="scss" scoped></style>
