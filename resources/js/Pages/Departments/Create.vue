<template>
    <Head title="Crear nuevo departamento" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Nuevo departamento</h2>
        </template>
        <div class="container py-7">
            <div class="card">
                <!-- Formulario para crear departamento -->
                <DepartmentForm />
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import DepartmentForm from '@/Components/DepartmentForm.vue';

export default {
    name: 'CreateDepartment',
    components: {
        AuthenticatedLayout,
        Head,
        DepartmentForm
    },
    data() {
        return {
            errors: {},
        };
    },
};
</script>