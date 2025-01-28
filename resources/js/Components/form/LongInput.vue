<template>
    <div ref="componentRef" class="relative" @click.stop="handleClick">
        <!-- Mode Edition -->
        <div v-if="isEditing" class="space-y-2" @click.stop>
            <div class="flex items-center gap-4">
                <div
                    class="p-2 rounded drag-handle hover:bg-gray-100 cursor-grab active:cursor-grabbing"
                    @click.stop
                >
                    <font-awesome-icon
                        icon="fa-solid fa-grip-vertical"
                        class="text-gray-400"
                    />
                </div>
                <font-awesome-icon
                    icon="fa-solid fa-align-left"
                    class="text-gray-500"
                />
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
            <Textarea placeholder="Réponse longue" disabled />
        </div>

        <!-- Mode Preview -->
        <div v-else class="space-y-2">
            <div class="flex items-center gap-2">
                <div
                    class="p-2 rounded drag-handle hover:bg-gray-100 cursor-grab active:cursor-grabbing"
                    @click.stop
                >
                    <font-awesome-icon
                        icon="fa-solid fa-grip-vertical"
                        class="text-gray-400"
                    />
                </div>
                <span class="text-sm font-medium text-gray-500"
                    >{{ index }}.</span
                >
                <p class="font-medium">
                    {{ element.question || "Question ?" }}
                </p>
            </div>
            <Textarea
                placeholder="Réponse longue"
                disabled
                class="bg-gray-50"
            />
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import { Input } from "@/Components/ui/input";
import { Button } from "@/Components/ui/button";
import { Textarea } from "@/Components/ui/textarea";

const emit = defineEmits(["select"]);

const componentRef = ref(null);
const props = defineProps({
    element: Object,
    onDelete: Function,
    isEditing: Boolean,
    index: Number,
});

const handleClick = (event) => {
    event.stopPropagation();
    if (!event.target.closest(".drag-handle")) {
        emit("select", props.element.id, event);
    }
};
</script>
