<template>
    <div class="space-y-2">
        <div class="flex items-center gap-4">
            <div class="drag-handle cursor-move p-2 hover:bg-gray-100 rounded">
                <font-awesome-icon
                    icon="fa-solid fa-grip-vertical"
                    class="text-gray-400"
                />
            </div>
            <font-awesome-icon icon="fa-solid fa-table" class="text-gray-500" />
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
        <div class="space-y-4">
            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead>Options</TableHead>
                        <TableHead
                            v-for="(column, colIndex) in element.tableData
                                .columns"
                            :key="colIndex"
                        >
                            <div class="flex items-center space-x-2">
                                <Input
                                    v-model="
                                        element.tableData.columns[colIndex]
                                    "
                                    class="w-32"
                                    placeholder="Nom de la colonne"
                                />
                                <div
                                    class="p-2 rounded hover:bg-gray-100 cursor-pointer transition-colors"
                                    @click="
                                        emit('removeColumn', element, colIndex)
                                    "
                                >
                                    <font-awesome-icon
                                        icon="fa-solid fa-minus"
                                        class="text-gray-500"
                                    />
                                </div>
                            </div>
                        </TableHead>
                        <TableHead>
                            <div
                                class="p-2 rounded hover:bg-gray-100 cursor-pointer transition-colors inline-flex items-center gap-2"
                                @click="emit('addColumn', element)"
                            >
                                <font-awesome-icon
                                    icon="fa-solid fa-plus"
                                    class="text-gray-500"
                                />
                                <span class="text-gray-600 text-sm"
                                    >Ajouter une colonne</span
                                >
                            </div>
                        </TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow
                        v-for="(row, rowIndex) in element.tableData.rows"
                        :key="rowIndex"
                    >
                        <TableCell>
                            <div class="flex items-center space-x-2">
                                <Input
                                    v-model="element.tableData.rows[rowIndex]"
                                    class="w-32"
                                    placeholder="Question"
                                />
                                <div
                                    class="p-2 rounded hover:bg-gray-100 cursor-pointer transition-colors"
                                    @click="
                                        emit('removeRow', element, rowIndex)
                                    "
                                >
                                    <font-awesome-icon
                                        icon="fa-solid fa-minus"
                                        class="text-gray-500"
                                    />
                                </div>
                            </div>
                        </TableCell>
                        <TableCell
                            v-for="(column, colIndex) in element.tableData
                                .columns"
                            :key="colIndex"
                        >
                            <RadioGroup :name="`row-${rowIndex}`">
                                <RadioGroupItem :value="colIndex.toString()" />
                            </RadioGroup>
                        </TableCell>
                        <TableCell></TableCell>
                    </TableRow>
                </TableBody>
            </Table>
            <div
                class="p-2 rounded hover:bg-gray-100 cursor-pointer transition-colors inline-flex items-center gap-2"
                @click="emit('addRow', element)"
            >
                <font-awesome-icon
                    icon="fa-solid fa-plus"
                    class="text-gray-500"
                />
                <span class="text-gray-600 text-sm">Ajouter une ligne</span>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Input } from "@/Components/ui/input";
import { Button } from "@/Components/ui/button";
import {
    Table,
    TableHeader,
    TableRow,
    TableHead,
    TableBody,
    TableCell,
} from "@/Components/ui/table";
import { RadioGroup, RadioGroupItem } from "@/Components/ui/radio-group";

const props = defineProps({
    element: Object,
    onDelete: Function,
});

const emit = defineEmits(["addColumn", "addRow", "removeColumn", "removeRow"]);
</script>

<style scoped></style>
