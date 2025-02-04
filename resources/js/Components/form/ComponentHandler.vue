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

            <!-- Checkbox -->
            <div
                v-else-if="props.question.component.type === 'checkbox'"
                class="space-y-2 grow"
            >
                <Label
                    class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                >
                    {{ props.question.label }}
                </Label>
                <div class="space-y-2">
                    <div
                        v-for="option in props.question.options"
                        :key="option.id"
                        class="flex items-center space-x-2"
                    >
                        <Checkbox
                            :id="`checkbox-${props.question.id}-${option.id}`"
                            :value="option.name"
                            @update:checked="
                                (checked) =>
                                    $emit(
                                        'update-answer',
                                        {
                                            checked: checked,
                                            name: option.name,
                                        },
                                        question.id
                                    )
                            "
                        />
                        <Label
                            :for="`checkbox-${props.question.id}-${option.id}`"
                            class="text-sm font-medium leading-none"
                        >
                            {{ option.name }}
                        </Label>
                    </div>
                </div>
            </div>

            <!-- Table Radio -->
            <div
                v-else-if="props.question.component.type === 'table_radio'"
                class="space-y-2 grow"
            >
                <Label
                    class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                >
                    {{ props.question.label }}
                </Label>
                <table class="w-full">
                    <thead>
                        <tr>
                            <th></th>
                            <th
                                v-for="column in getColumns(
                                    props.question.options
                                )"
                                :key="column.id"
                            >
                                {{ column.name }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="row in getRows(props.question.options)"
                            :key="row.id"
                        >
                            <td>{{ row.name }}</td>
                            <td
                                v-for="column in getColumns(
                                    props.question.options
                                )"
                                :key="column.id"
                            >
                                <input
                                    type="radio"
                                    :name="`radio-${props.question.id}-${row.id}`"
                                    :value="column.name"
                                    @change="
                                        $emit(
                                            'update-answer',
                                            {
                                                option_id: row.id,
                                                response: column.name,
                                            },
                                            props.question.id
                                        )
                                    "
                                />
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Label } from "@/Components/ui/label";
import { Input } from "@/Components/ui/input";
import { Textarea } from "@/Components/ui/textarea";
import { RadioGroup, RadioGroupItem } from "@/Components/ui/radio-group";
import { Checkbox } from "@/Components/ui/checkbox";

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

const getColumns = (options) => {
    return options.filter((option) => option.type === "column");
};

const getRows = (options) => {
    return options.filter((option) => option.type === "row");
};
</script>

<style scoped>
table {
    border-collapse: collapse;
    width: 100%;
}

th,
td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: center;
}

th {
    background-color: #f2f2f2;
}
</style>
