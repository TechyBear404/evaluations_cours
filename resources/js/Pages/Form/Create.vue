<template>
    <AppLayout title="Créer un nouveau formulaire">
        <div class="container p-6 mx-auto">
            <!-- Form Header -->
            <Card class="mb-6">
                <CardHeader>
                    <CardTitle class="text-2xl font-bold">
                        <font-awesome-icon
                            icon="fa-solid fa-pen-to-square"
                            class="mr-2"
                        />
                        Créer un nouveau formulaire
                    </CardTitle>
                </CardHeader>
                <CardContent>
                    <div class="space-y-4">
                        <div>
                            <Label for="title">Titre du formulaire</Label>
                            <Input
                                id="title"
                                v-model="form.title"
                                placeholder="Entrez le titre du formulaire"
                                class="mt-1"
                            />
                        </div>
                    </div>
                </CardContent>
            </Card>

            <div class="grid grid-cols-12 gap-6">
                <!-- Components Panel -->
                <div class="col-span-3">
                    <Card>
                        <CardHeader>
                            <CardTitle>
                                <font-awesome-icon
                                    icon="fa-solid fa-puzzle-piece"
                                    class="mr-2"
                                />
                                Composants
                            </CardTitle>
                        </CardHeader>
                        <CardContent>
                            <draggable
                                :list="props.components"
                                :group="{
                                    name: 'components',
                                    pull: 'clone',
                                    put: false,
                                }"
                                item-key="id"
                                :clone="cloneComponent"
                                class="space-y-2"
                            >
                                <template #item="{ element }">
                                    <div
                                        class="p-3 transition-all border border-gray-200 rounded-md cursor-move bg-gray-50 hover:bg-gray-100"
                                    >
                                        <div
                                            class="flex items-center space-x-2"
                                        >
                                            <font-awesome-icon
                                                :icon="
                                                    getComponentIcon(
                                                        element.type
                                                    )
                                                "
                                                class="text-gray-500"
                                            />
                                            <span>{{ element.name }}</span>
                                        </div>
                                    </div>
                                </template>
                            </draggable>
                        </CardContent>
                    </Card>
                </div>

                <!-- Builder Area -->
                <div class="col-span-9">
                    <Card>
                        <CardHeader>
                            <CardTitle>
                                <font-awesome-icon
                                    icon="fa-solid fa-layer-group"
                                    class="mr-2"
                                />
                                Constructeur de formulaire
                            </CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div
                                class="min-h-[600px] border-2 border-dashed border-gray-200 rounded-lg p-4"
                            >
                                <draggable
                                    v-model="formComponents"
                                    group="components"
                                    item-key="id"
                                    class="space-y-3"
                                >
                                    <template #item="{ element }">
                                        <div
                                            class="p-4 bg-white border rounded-md shadow-sm"
                                        >
                                            <div class="pt-4">
                                                <ShortInput
                                                    v-if="
                                                        element.type === 'input'
                                                    "
                                                    :element="element"
                                                    :onDelete="handleDelete"
                                                />
                                                <LongInput
                                                    v-else-if="
                                                        element.type ===
                                                        'textarea'
                                                    "
                                                    :element="element"
                                                    :onDelete="handleDelete"
                                                />
                                                <SingleChoice
                                                    v-else-if="
                                                        element.type === 'radio'
                                                    "
                                                    :element="element"
                                                    :onDelete="handleDelete"
                                                    @addOption="addOption"
                                                    @removeOption="removeOption"
                                                />
                                                <MultipleChoice
                                                    v-else-if="
                                                        element.type ===
                                                        'checkbox'
                                                    "
                                                    :element="element"
                                                    :onDelete="handleDelete"
                                                    @addOption="addOption"
                                                    @removeOption="removeOption"
                                                />
                                                <TableChoice
                                                    v-else-if="
                                                        element.type ===
                                                        'table_radio'
                                                    "
                                                    :element="element"
                                                    :onDelete="handleDelete"
                                                    @addColumn="addTableColumn"
                                                    @removeColumn="
                                                        removeTableColumn
                                                    "
                                                    @addRow="addTableRow"
                                                    @removeRow="removeTableRow"
                                                />
                                            </div>
                                        </div>
                                    </template>
                                    <template
                                        #footer
                                        v-if="formComponents.length === 0"
                                    >
                                        <div
                                            class="flex flex-col items-center justify-center py-12 text-gray-400"
                                        >
                                            <font-awesome-icon
                                                icon="fa-solid fa-arrow-down"
                                                class="mb-2 text-3xl"
                                            />
                                            <p>
                                                Glissez et déposez les
                                                composants ici
                                            </p>
                                        </div>
                                    </template>
                                </draggable>
                            </div>

                            <div class="flex justify-end mt-6 space-x-2">
                                <Button
                                    variant="outline"
                                    :href="route('form.index')"
                                >
                                    <font-awesome-icon
                                        icon="fa-solid fa-times"
                                        class="mr-2"
                                    />
                                    Annuler
                                </Button>
                                <Button
                                    @click="handleSave"
                                    :disabled="form.processing"
                                >
                                    <font-awesome-icon
                                        icon="fa-solid fa-save"
                                        class="mr-2"
                                    />
                                    Enregistrer
                                </Button>
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import draggable from "vuedraggable";
import { Button } from "@/Components/ui/button";
import { Input } from "@/Components/ui/input";
import { Label } from "@/Components/ui/label";
import { Card, CardContent, CardHeader, CardTitle } from "@/Components/ui/card";
import ShortInput from "@/Components/form/ShortInput.vue";
import LongInput from "@/Components/form/LongInput.vue";
import SingleChoice from "@/Components/form/SingleChoice.vue";
import MultipleChoice from "@/Components/form/MultipleChoice.vue";
import TableChoice from "@/Components/form/TableChoice.vue";

const props = defineProps({
    components: {
        type: Array,
        required: true,
    },
});

const form = useForm({
    title: "",
    description: "",
    components: [],
});

const formComponents = ref([]);

const cloneComponent = (item) => ({
    ...item,
    id: `temp-${Date.now()}`,
    question: "",
    options: item.type === "radio" || item.type === "checkbox" ? ["", ""] : [],
    tableData:
        item.type === "table_radio"
            ? {
                  columns: ["", ""],
                  rows: [""],
              }
            : null,
});

// Add new methods for managing options
const addOption = (element) => {
    if (!element.options) {
        element.options = [];
    }
    element.options.push("");
};

const removeOption = (element, index) => {
    element.options.splice(index, 1);
};

// Add methods for table management
const addTableColumn = (element) => {
    element.tableData.columns.push("");
};

const addTableRow = (element) => {
    element.tableData.rows.push("");
};

const removeTableColumn = (element, index) => {
    element.tableData.columns.splice(index, 1);
};

const removeTableRow = (element, index) => {
    element.tableData.rows.splice(index, 1);
};

const handleSave = () => {
    // Validation basique
    if (!form.title.trim()) {
        alert("Le titre du formulaire est requis");
        return;
    }

    if (formComponents.value.length === 0) {
        alert("Ajoutez au moins une question au formulaire");
        return;
    }

    // Validation des questions
    const invalidComponents = formComponents.value.filter(
        (component) => !component.question.trim()
    );
    if (invalidComponents.length > 0) {
        alert("Toutes les questions doivent avoir un libellé");
        return;
    }

    // Validation des options pour les composants qui en nécessitent
    const invalidOptions = formComponents.value.filter((component) => {
        if (component.type === "radio" || component.type === "checkbox") {
            return !component.options || component.options.length < 2;
        }
        if (component.type === "table_radio") {
            return (
                !component.tableData ||
                !component.tableData.columns.length ||
                !component.tableData.rows.length
            );
        }
        return false;
    });

    if (invalidOptions.length > 0) {
        alert("Les questions à choix doivent avoir au moins deux options");
        return;
    }

    // Si tout est valide, on soumet le formulaire
    form.components = formComponents.value;
    form.post(route("form.store"), {
        onSuccess: () => {
            // Redirection gérée par le contrôleur
        },
        onError: (errors) => {
            console.error(errors);
            alert("Une erreur est survenue lors de la sauvegarde");
        },
    });
};

const handleDelete = (id) => {
    formComponents.value = formComponents.value.filter((c) => c.id !== id);
};

const getComponentIcon = (type) => {
    switch (type) {
        case "input":
            return "fa-solid fa-font";
        case "textarea":
            return "fa-solid fa-align-left";
        case "radio":
            return "fa-solid fa-circle-dot";
        case "checkbox":
            return "fa-solid fa-square-check";
        case "table_radio":
            return "fa-solid fa-table";
        default:
            return "fa-solid fa-puzzle-piece";
    }
};

const handleSort = (e) => {
    console.log("Component reordered:", e);
};
</script>

<style scoped>
.drag-handle {
    touch-action: none;
}
</style>
