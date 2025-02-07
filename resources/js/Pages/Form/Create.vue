<template>
    <AppLayout title="Créer un nouveau formulaire">
        <!-- Modifiez le conteneur principal pour ajouter @click -->
        <div class="container p-6 mx-auto" @click="clearEditMode">
            <!-- Ajoutez @click.stop sur le Card pour éviter la propagation -->
            <Card class="mb-6" @click.stop>
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
                        <div>
                            <Label for="title">Entète du formulaire</Label>
                            <Textarea
                                id="description"
                                v-model="form.description"
                                placeholder="Entrez l'entête du formulaire"
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
                                                    element.type === 'input'
                                                        ? 'fa-solid fa-font'
                                                        : element.type ===
                                                          'textarea'
                                                        ? 'fa-solid fa-align-left'
                                                        : element.type ===
                                                          'radio'
                                                        ? 'fa-solid fa-circle-dot'
                                                        : element.type ===
                                                          'checkbox'
                                                        ? 'fa-solid fa-square-check'
                                                        : element.type ===
                                                          'table_radio'
                                                        ? 'fa-solid fa-table'
                                                        : 'fa-solid fa-puzzle-piece'
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
                                Constructeur de formulaire
                            </CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div class="space-y-4">
                                <draggable
                                    v-model="formComponents"
                                    group="components"
                                    item-key="id"
                                    handle=".drag-handle"
                                    class="space-y-3"
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
                                                @addOption="
                                                    (element) =>
                                                        addOption(element)
                                                "
                                                @removeOption="
                                                    (element, index) =>
                                                        removeOption(
                                                            element,
                                                            index
                                                        )
                                                "
                                                @onAddColumn="
                                                    (element) =>
                                                        addTableColumn(element)
                                                "
                                                @onRemoveColumn="
                                                    (element, index) =>
                                                        removeTableColumn(
                                                            element,
                                                            index
                                                        )
                                                "
                                                @onAddRow="
                                                    (element) =>
                                                        addTableRow(element)
                                                "
                                                @onRemoveRow="
                                                    (element, index) =>
                                                        removeTableRow(
                                                            element,
                                                            index
                                                        )
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
import { ref, onMounted, onUnmounted } from "vue";
import { useForm } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import draggable from "vuedraggable";
import { Button } from "@/Components/ui/button";
import { Input } from "@/Components/ui/input";
import { Label } from "@/Components/ui/label";
import { Textarea } from "@/Components/ui/textarea";
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
const editingComponentId = ref(null);

const cloneComponent = (item) => ({
    ...item,
    sourceId: item.id, // Garde l'ID original
    tempId: `temp-${Date.now()}`, // ID temporaire pour la gestion frontend
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
    // Validation
    if (!isFormValid()) return;

    // Préparer les données
    const componentsToSave = prepareComponentsForSave();

    // Soumettre le formulaire
    form.components = componentsToSave;
    form.post(route("form.store"));
};

const isFormValid = () => {
    if (!form.title.trim()) {
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
        sourceId: component.sourceId,
        options: component.options?.filter(Boolean),
        tableData: component.tableData
            ? {
                  columns: component.tableData.columns.filter(Boolean),
                  rows: component.tableData.rows.filter(Boolean),
              }
            : undefined,
    }));
};

const handleDelete = (id) => {
    console.log("Deleting component with id:", id);
    formComponents.value = formComponents.value.filter((c) => c.id !== id);
};

const clearEditMode = () => {
    // Ajoutez un console.log pour déboguer
    console.log("Clearing edit mode");
    editingComponentId.value = null;
};

// Modifiez setEditMode pour être plus explicite avec la propagation
const setEditMode = (id, event) => {
    if (event) {
        event.stopPropagation();
    }
    console.log("Setting edit mode:", id);
    editingComponentId.value = id;
};

// Ajouter aux props existants
const componentProps = {
    element: Object,
    onDelete: Function,
    isEditing: Boolean,
    onFocus: Function,
    onBlur: Function,
    index: Number, // Ajouter cette prop
};

const isPanelCollapsed = ref(false);

const togglePanel = () => {
    isPanelCollapsed.value = !isPanelCollapsed.value;
};

const getComponentProps = (element) => {
    const baseProps = {};

    if (["radio", "checkbox"].includes(element.type)) {
        baseProps.addOption = addOption;
        baseProps.removeOption = removeOption;
    } else if (element.type === "table_radio") {
        baseProps.onAddColumn = addTableColumn; // Changé pour correspondre aux événements du composant
        baseProps.onRemoveColumn = removeTableColumn;
        baseProps.onAddRow = addTableRow;
        baseProps.onRemoveRow = removeTableRow;
    }

    return baseProps;
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
