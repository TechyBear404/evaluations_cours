<template>
    <div class="space-y-2">
        <div class="flex items-center gap-4">
            <div class="drag-handle cursor-move p-2 hover:bg-gray-100 rounded">
                <font-awesome-icon
                    icon="fa-solid fa-grip-vertical"
                    class="text-gray-400"
                />
            </div>
            <font-awesome-icon
                icon="fa-solid fa-circle-dot"
                class="text-gray-500"
            />
            <Input
                v-model="element.question"
                placeholder="Entrez votre question"
                class="flex-1"
            />
            <Button
                variant="destructive"
                size="icon"
                @click="onDelete(element.id)"
            >
                <font-awesome-icon icon="fa-solid fa-trash" />
            </Button>
        </div>
        <RadioGroup>
            <div class="flex flex-col space-y-2">
                <div
                    v-for="(option, index) in element.options"
                    :key="index"
                    class="flex items-center space-x-2"
                >
                    <RadioGroupItem :value="option" />
                    <Input
                        v-model="element.options[index]"
                        placeholder="Nouvelle option"
                        class="w-40"
                    />
                    <div
                        class="p-2 rounded hover:bg-gray-100 cursor-pointer transition-colors"
                        @click="emit('removeOption', element, index)"
                    >
                        <font-awesome-icon
                            icon="fa-solid fa-minus"
                            class="text-gray-500"
                        />
                    </div>
                </div>
            </div>
        </RadioGroup>
        <div
            class="p-2 rounded hover:bg-gray-100 cursor-pointer transition-colors inline-flex items-center gap-2"
            @click="emit('addOption', element)"
        >
            <font-awesome-icon icon="fa-solid fa-plus" class="text-gray-500" />
            <span class="text-gray-600 text-sm">Ajouter une option</span>
        </div>
    </div>
</template>

<script setup>
import { Input } from "@/Components/ui/input";
import { Button } from "@/Components/ui/button";
import { RadioGroup, RadioGroupItem } from "@/Components/ui/radio-group";

const props = defineProps({
    element: Object,
    onDelete: Function,
});

const emit = defineEmits(["addOption", "removeOption"]);
</script>

<style scoped></style>
