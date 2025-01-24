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
                    icon="fa-solid fa-square-check"
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
            <div class="space-y-2">
                <div
                    v-for="(option, index) in element.options"
                    :key="index"
                    class="flex items-center space-x-2"
                >
                    <Checkbox :id="`checkbox-${element.id}-${index}`" />
                    <Input
                        v-model="element.options[index]"
                        :placeholder="`Option ${index + 1}`"
                        class="w-40"
                    />
                    <div
                        class="p-2 transition-colors rounded cursor-pointer hover:bg-gray-100"
                        @click="emit('removeOption', element, index)"
                    >
                        <font-awesome-icon
                            icon="fa-solid fa-minus"
                            class="text-gray-500"
                        />
                    </div>
                </div>
            </div>
            <div
                class="inline-flex items-center gap-2 p-2 transition-colors rounded cursor-pointer hover:bg-gray-100"
                @click="emit('addOption', element)"
            >
                <font-awesome-icon
                    icon="fa-solid fa-plus"
                    class="text-gray-500"
                />
                <span class="text-sm text-gray-600">Ajouter une option</span>
            </div>
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
            <div class="space-y-2">
                <div
                    v-for="(option, index) in element.options"
                    :key="index"
                    class="flex items-center space-x-2"
                >
                    <Checkbox
                        :id="`checkbox-preview-${element.id}-${index}`"
                        disabled
                    />
                    <span class="text-gray-600"
                        >{{ option || `Option ${index + 1}` }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import { Input } from "@/Components/ui/input";
import { Button } from "@/Components/ui/button";
import { Checkbox } from "@/Components/ui/checkbox";

const emit = defineEmits(["select", "addOption", "removeOption"]);

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

<style scoped></style>
