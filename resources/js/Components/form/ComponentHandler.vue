<template>
    <div class="w-full p-4 space-y-6 rounded-lg">
        <div class="flex space-x-2">
            <p>{{ number + 1 }}.</p>
            <!-- Input -->
            <div
                v-if="props.question.component.type === 'input'"
                class="space-y-2 grow"
            >
                <Label
                    class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                >
                    {{ props.question.label }}
                </Label>
                <Input
                    type="text"
                    @input="
                        $emit(
                            'updateAnswer',
                            $event.target.value,
                            props.question.id
                        )
                    "
                    class="w-full transition-all duration-200 focus:ring-2 focus:ring-primary-500"
                    placeholder="Votre réponse..."
                />
            </div>

            <!-- Textarea -->
            <div
                v-else-if="props.question.component.type === 'textarea'"
                class="space-y-2 grow"
            >
                <Label
                    class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                >
                    {{ props.question.label }}
                </Label>
                <Textarea
                    placeholder="Votre réponse..."
                    class="w-full min-h-[100px] transition-all duration-200 focus:ring-2 focus:ring-primary-500"
                    @input="
                        $emit(
                            'updateAnswer',
                            $event.target.value,
                            props.question.id
                        )
                    "
                />
            </div>

            <!-- Radio -->
            <div
                v-else-if="props.question.component.type === 'radio'"
                class="space-y-2 grow"
            >
                <Label
                    class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                >
                    {{ props.question.label }}
                </Label>
                <RadioGroup
                    @update:modelValue="
                        (value) => $emit('update-answer', value, question.id)
                    "
                >
                    <div
                        v-for="option in props.question.options"
                        :key="option.id"
                        class="flex items-center space-x-2"
                    >
                        <RadioGroupItem
                            :value="option.name"
                            :id="`radio-${props.question.id}-${option.id}`"
                        />
                        <Label
                            :for="`radio-${props.question.id}-${option.id}`"
                            >{{ option.name }}</Label
                        >
                    </div>
                </RadioGroup>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Label } from "@/Components/ui/label";
import { Input } from "@/Components/ui/input";
import { Textarea } from "@/Components/ui/textarea";
import { RadioGroup, RadioGroupItem } from "@/Components/ui/radio-group";

const props = defineProps({
    question: {
        type: Object,
        required: true,
    },
    number: {
        type: Number,
        required: true,
    },
});
</script>
