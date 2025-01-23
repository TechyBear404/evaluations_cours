<template>
    <div class="space-y-2">
        <div class="flex items-center gap-4">
            <div class="drag-handle cursor-move p-2 hover:bg-gray-100 rounded">
                <font-awesome-icon
                    icon="fa-solid fa-grip-vertical"
                    class="text-gray-400"
                />
            </div>
            <font-awesome-icon icon="fa-solid fa-font" class="text-gray-500" />
            <Input
                v-model="element.question"
                placeholder="Entrez votre question"
                class="flex-1"
            />
            <Button
                variant="destructive"
                size="sm"
                @click="onDelete(element.id)"
            >
                <font-awesome-icon icon="fa-solid fa-trash" />
            </Button>
        </div>
        <Input placeholder="Réponse courte" disabled />
    </div>
</template>

<script setup>
import { Input } from "@/Components/ui/input";
import { Button } from "@/Components/ui/button";

const props = defineProps({
    element: Object,
    onDelete: Function,
});
</script>

<style scoped></style>
