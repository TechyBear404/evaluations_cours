<template>
    <div class="w-full p-4 space-y-6 rounded-lg">
        <div class="flex space-x-2">
            <!-- Input -->
            <div
                v-if="props.question.component.type === 'input'"
                class="space-y-2 grow"
            >
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
                            style="border-radius: 3px"
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
                class="space-y-2 overflow-hidden border rounded-lg grow"
            >
                <Table>
                    <TableHeader class="border-b bg-primary/90">
                        <TableRow>
                            <TableHead
                                class="w-[200px] font-semibold"
                            ></TableHead>
                            <TableHead
                                v-for="column in getColumns(
                                    props.question.options
                                )"
                                :key="column.id"
                                class="text-lg font-semibold text-center text-primary-foreground"
                            >
                                {{ column.name }}
                            </TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow
                            v-for="row in getRows(props.question.options)"
                            :key="row.id"
                            class="transition-colors hover:bg-muted/50"
                        >
                            <TableCell class="font-medium">{{
                                row.name
                            }}</TableCell>
                            <TableCell
                                v-for="column in getColumns(
                                    props.question.options
                                )"
                                :key="column.id"
                                class="text-center"
                            >
                                <div class="flex justify-center">
                                    <RadioGroup
                                        class="flex items-center justify-center"
                                    >
                                        <RadioGroupItem
                                            :value="column.name"
                                            :id="`radio-${props.question.id}-${row.id}-${column.id}`"
                                            :name="`radio-${props.question.id}-${row.id}`"
                                            class="data-[state=checked]:border-primary data-[state=checked]:bg-primary"
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
                                    </RadioGroup>
                                </div>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
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
import {
    Table,
    TableHeader,
    TableBody,
    TableHead,
    TableRow,
    TableCell,
} from "@/Components/ui/table";

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
/* Remove the existing table styles since we're using shadcn components */
</style>
