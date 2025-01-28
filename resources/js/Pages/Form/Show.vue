<template>
    <AppLayout :name="`Modifier ${form.name}`">
        <div class="container p-6 mx-auto" @click="clearEditMode">
            <!-- Form Header -->
            <Card class="mb-6" @click.stop>
                <CardHeader>
                    <CardTitle class="text-2xl font-bold">
                        <font-awesome-icon
                            icon="fa-solid fa-pen-to-square"
                            class="mr-2"
                        />
                        Modifier {{ form.name }}
                    </CardTitle>
                </CardHeader>
                <CardContent>
                    <div class="space-y-4">
                        <div>
                            <Label for="name">Titre du formulaire</Label>
                            <Input
                                id="name"
                                v-model="form.name"
                                placeholder="Entrez le titre du formulaire"
                                class="mt-1"
                            />
                        </div>
                    </div>
                </CardContent>
            </Card>

            <div class="grid grid-cols-12 gap-6">
                <!-- Components Panel -->
                <div
                    :class="[
                        'transition-all duration-300',
                        isPanelCollapsed ? 'col-span-1' : 'col-span-3',
                    ]"
                    @click.stop
                >
                    <Card>
                        <CardHeader>
                            <div class="flex items-center justify-between">
                                <CardTitle class="flex items-center">
                                    <font-awesome-icon
                                        icon="fa-solid fa-puzzle-piece"
                                        class="text-gray-500"
                                        :class="{ 'mr-2': !isPanelCollapsed }"
                                    />
                                    <span
                                        :class="[
                                            isPanelCollapsed
                                                ? 'hidden'
                                                : 'flex items-center',
                                        ]"
                                    >
                                        Composants
                                    </span>
                                </CardTitle>
                                <Button
                                    variant="ghost"
                                    size="sm"
                                    @click="togglePanel"
                                >
                                    <font-awesome-icon
                                        :icon="
                                            isPanelCollapsed
                                                ? 'fa-solid fa-chevron-right'
                                                : 'fa-solid fa-chevron-left'
                                        "
                                        class="text-gray-500"
                                    />
                                </Button>
                            </div>
                        </CardHeader>
                        <CardContent>
                            <draggable
                                :list="components"
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
                                        class="p-3 transition-all border border-gray-200 rounded-md bg-gray-50 hover:bg-gray-100 cursor-grab active:cursor-grabbing"
                                    >
                                        <div
                                            class="flex items-center"
                                            :class="{
                                                'justify-center':
                                                    isPanelCollapsed,
                                            }"
                                        >
                                            <font-awesome-icon
                                                :icon="
                                                    getComponentIcon(
                                                        element.type
                                                    )
                                                "
                                                class="text-gray-500"
                                            />
                                            <span
                                                v-if="!isPanelCollapsed"
                                                class="ml-2"
                                                >{{ element.name }}</span
                                            >
                                        </div>
                                    </div>
                                </template>
                            </draggable>
                        </CardContent>
                    </Card>
                </div>

                <!-- Builder Area -->
                <div
                    :class="[
                        'transition-all duration-300',
                        isPanelCollapsed ? 'col-span-11' : 'col-span-9',
                    ]"
                    @click.stop
                >
                    <Card>
                        <CardHeader>
                            <CardTitle>
                                <font-awesome-icon
                                    icon="fa-solid fa-layer-group"
                                    class="mr-2"
                                />
                                Questions du formulaire
                            </CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div class="space-y-4">
                                <draggable
                                    v-model="formComponents"
                                    group="components"
                                    handle=".drag-handle"
                                    class="space-y-3"
                                    item-key="id"
                                >
                                    <template #item="{ element, index }">
                                        <div
                                            class="p-4 transition-colors bg-white border border-gray-200 rounded-md shadow-sm hover:border-primary/50"
                                        >
                                            <component
                                                :is="
                                                    getComponentType(
                                                        element.type
                                                    )
                                                "
                                                :element="element"
                                                :index="index + 1"
                                                :onDelete="handleDelete"
                                                :isEditing="
                                                    editingComponentId ===
                                                    element.id
                                                "
                                                @select="setEditMode"
                                                v-bind="
                                                    getComponentProps(element)
                                                "
                                            />
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
                                <Link :href="route('form.index')">
                                    <Button variant="outline">
                                        <font-awesome-icon
                                            icon="fa-solid fa-times"
                                            class="mr-2"
                                        />
                                        Annuler
                                    </Button>
                                </Link>
                                <Button
                                    @click="handleSave"
                                    :disabled="formComponents.processing"
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
import { ref, watch } from "vue";
import { useForm, Link } from "@inertiajs/vue3";
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
    form: {
        type: Object,
        required: true,
    },
    components: {
        type: Array,
        required: true,
    },
});

console.log(props.form);

// Correction de l'initialisation du formulaire et des composants
const formComponents = ref(props.form.components || []);

const form = useForm({
    name: props.form.name,
    components: props.form.components || [],
});

// Suppression de la watch qui causait des problèmes
// watch(
//     () => props.form.components,
//     (newComponents) => {
//         formComponents = newComponents;
//     },
//     { deep: true }
// );

const editingComponentId = ref(null);

const clearEditMode = () => {
    editingComponentId.value = null;
};

const setEditMode = (id, event) => {
    if (event) {
        event.stopPropagation();
    }
    editingComponentId.value = id;
};

const getComponentType = (type) => {
    const componentMap = {
        input: ShortInput,
        textarea: LongInput,
        radio: SingleChoice,
        checkbox: MultipleChoice,
        table_radio: TableChoice,
    };
    return componentMap[type];
};

// Ajout des méthodes manquantes pour la gestion des options
const addOption = (element) => {
    if (!element.options) {
        element.options = [];
    }
    element.options.push("");
};

const removeOption = (element, index) => {
    element.options.splice(index, 1);
};

// Ajout des méthodes pour la gestion des tables
const addTableColumn = (element) => {
    if (!element.tableData) {
        element.tableData = { columns: [], rows: [] };
    }
    element.tableData.columns.push("");
};

const removeTableColumn = (element, index) => {
    element.tableData.columns.splice(index, 1);
};

const addTableRow = (element) => {
    if (!element.tableData) {
        element.tableData = { columns: [], rows: [] };
    }
    element.tableData.rows.push("");
};

const removeTableRow = (element, index) => {
    element.tableData.rows.splice(index, 1);
};

const getComponentProps = (element) => {
    const baseProps = {};

    if (["radio", "checkbox"].includes(element.type)) {
        baseProps.addOption = addOption;
        baseProps.removeOption = removeOption;
    } else if (element.type === "table_radio") {
        baseProps.onAddColumn = addTableColumn; // Changé de addColumn à onAddColumn
        baseProps.onRemoveColumn = removeTableColumn; // Changé de removeColumn à onRemoveColumn
        baseProps.onAddRow = addTableRow; // Changé de addRow à onAddRow
        baseProps.onRemoveRow = removeTableRow; // Changé de removeRow à onRemoveRow
    }

    return baseProps;
};

const handleDelete = (id) => {
    formComponents.components = formComponents.components.filter(
        (c) => c.id !== id
    );
};

const handleSave = () => {
    // Validation
    if (!isFormValid()) return;

    // Mise à jour du formulaire avec les données actuelles
    form.components = prepareComponentsForSave();

    // Soumettre le formulaire
    form.put(route("form.update", props.form.id));
};

const isFormValid = () => {
    if (!form.name.trim()) {
        alert("Le titre du formulaire est requis");
        return false;
    }

    if (formComponents.value.length === 0) {
        alert("Ajoutez au moins une question au formulaire");
        return false;
    }

    const hasInvalidQuestions = formComponents.value.some(
        (component) => !component.question?.trim()
    );
    if (hasInvalidQuestions) {
        alert("Toutes les questions doivent avoir un libellé");
        return false;
    }

    const hasInvalidOptions = formComponents.value.some((component) => {
        if (["radio", "checkbox"].includes(component.type)) {
            return (
                !component.options ||
                component.options.filter(Boolean).length < 2
            );
        }
        if (component.type === "table_radio") {
            return (
                !component.tableData?.columns.filter(Boolean).length ||
                !component.tableData?.rows.filter(Boolean).length
            );
        }
        return false;
    });

    if (hasInvalidOptions) {
        alert(
            "Les questions à choix doivent avoir au moins deux options valides"
        );
        return false;
    }

    return true;
};

const prepareComponentsForSave = () => {
    return formComponents.value.map((component) => ({
        ...component,
        id: component.tempId,
        options: component.options?.filter(Boolean),
        tableData: component.tableData
            ? {
                  columns: component.tableData.columns.filter(Boolean),
                  rows: component.tableData.rows.filter(Boolean),
              }
            : undefined,
    }));
};

const isPanelCollapsed = ref(false);

const togglePanel = () => {
    isPanelCollapsed.value = !isPanelCollapsed.value;
};

const cloneComponent = (item) => ({
    ...item,
    tempId: item.id,
    id: `temp-${Date.now()}`,
    question: "",
    options: item.type === "radio" || item.type === "checkbox" ? ["", ""] : [],
    tableData:
        item.type === "table_radio" ? { columns: ["", ""], rows: [""] } : null,
});

const getComponentIcon = (type) => {
    const iconMap = {
        input: "fa-solid fa-font",
        textarea: "fa-solid fa-align-left",
        radio: "fa-solid fa-circle-dot",
        checkbox: "fa-solid fa-square-check",
        table_radio: "fa-solid fa-table",
    };
    return iconMap[type] || "fa-solid fa-puzzle-piece";
};
</script>

<style scoped>
.drag-handle {
    touch-action: none;
}

[data-dragging="true"] {
    cursor: grabbing !important;
}

.transition-all {
    transition-property: all;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 300ms;
}
</style>
