<template>
    <div @click.stop="onFocus">
        <!-- Mode Edition -->
        <div v-if="isEditing" class="space-y-2">
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
                    icon="fa-solid fa-table"
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
            <div class="space-y-4">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead class=""></TableHead>
                            <TableHead
                                v-for="(column, colIndex) in element.tableData
                                    .columns"
                                :key="colIndex"
                                class=""
                            >
                                <div class="flex items-center">
                                    <Input
                                        v-model="
                                            element.tableData.columns[colIndex]
                                        "
                                        class="w-32"
                                        :placeholder="`Colonne ${colIndex + 1}`"
                                    />
                                    <div
                                        class="p-2 transition-colors rounded cursor-pointer hover:bg-gray-100"
                                        @click="
                                            emit(
                                                'removeColumn',
                                                element,
                                                colIndex
                                            )
                                        "
                                    >
                                        <font-awesome-icon
                                            icon="fa-solid fa-minus"
                                            class="text-gray-500"
                                        />
                                    </div>
                                </div>
                            </TableHead>
                            <TableHead class="">
                                <div
                                    class="inline-flex items-center gap-2 p-2 transition-colors rounded cursor-pointer hover:bg-gray-100"
                                    @click="emit('addColumn', element)"
                                >
                                    <font-awesome-icon
                                        icon="fa-solid fa-plus"
                                        class="text-gray-500"
                                    />
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
                                        v-model="
                                            element.tableData.rows[rowIndex]
                                        "
                                        class="w-32"
                                        :placeholder="`Ligne ${rowIndex + 1}`"
                                    />
                                    <div
                                        class="p-2 transition-colors rounded cursor-pointer hover:bg-gray-100"
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
                                class=""
                            >
                                <RadioGroup
                                    :name="`row-${rowIndex}`"
                                    class="flex justify-center"
                                >
                                    <RadioGroupItem
                                        :value="colIndex.toString()"
                                    />
                                </RadioGroup>
                            </TableCell>
                            <TableCell></TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
                <div
                    class="inline-flex items-center gap-2 p-2 transition-colors rounded cursor-pointer hover:bg-gray-100"
                    @click="emit('addRow', element)"
                >
                    <font-awesome-icon
                        icon="fa-solid fa-plus"
                        class="text-gray-500"
                    />
                    <span class="text-sm text-gray-600">Ajouter une ligne</span>
                </div>
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
                    {{ element.question || "Question sans titre" }}
                </p>
            </div>
            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead>Options</TableHead>
                        <TableHead
                            v-for="(column, index) in element.tableData.columns"
                            :key="index"
                        >
                            {{ column || `Colonne ${index + 1}` }}
                        </TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow
                        v-for="(row, rowIndex) in element.tableData.rows"
                        :key="rowIndex"
                    >
                        <TableCell>{{
                            row || `Ligne ${rowIndex + 1}`
                        }}</TableCell>
                        <TableCell
                            v-for="(_, colIndex) in element.tableData.columns"
                            :key="colIndex"
                            class="text-center"
                        >
                            <RadioGroup :name="`preview-row-${rowIndex}`">
                                <RadioGroupItem
                                    :value="colIndex.toString()"
                                    disabled
                                />
                            </RadioGroup>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
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
    isEditing: Boolean,
    onFocus: Function,
    onBlur: Function,
    index: Number, // Ajout de l'index
});

const emit = defineEmits(["addColumn", "addRow", "removeColumn", "removeRow"]);
</script>

<style scoped></style>
