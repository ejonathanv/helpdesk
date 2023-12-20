<template>
    <Head title="Departamentos" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Departamentos</h2>
        </template>
        <div class="container py-7">
            <div class="card">
                <DepartmentsTable :departments="departments" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DepartmentsTable from '@/Components/DepartmentsTable.vue';
import { Head } from '@inertiajs/vue3';

export default {
    name: 'Departamentos',
    components: {
        AuthenticatedLayout,
        Head,
        DepartmentsTable,
    },
    props: {
        departments: {
            type: Array,
            required: true,
        },
    },
    data() {
        return {};
    },
};
</script>