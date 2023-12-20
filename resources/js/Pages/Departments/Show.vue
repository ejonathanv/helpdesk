<template>
    <Head title="Información del departamento" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Información del departamento
            </h2>
        </template>
        <div class="container py-7 space-y-6">
            <!-- Edicion de información general -->
            <DepartmentDetails :department="department" />

            <!-- Eliminar departamento -->
            <DepartmentDelete :department="department" />
        </div>
    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import DepartmentDetails from "@/Components/DepartmentDetails.vue";
import DepartmentDelete from "@/Components/DepartmentDelete.vue";

export default {
    name: "ShowDepartment",
    props: {
        department: {
            type: Object,
            required: true,
        },
    },
    components: {
        AuthenticatedLayout,
        Head,
        DepartmentDetails,
        DepartmentDelete,
    },
};
</script>
