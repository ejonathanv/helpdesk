<template>
    <Head title="Ingenieros" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Ingenieros</h2>
        </template>
        <div class="container py-7">
            <div class="card">
                <AgentsTable :agents="agents" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import AgentsTable from '@/Components/AgentsTable.vue';
import { Head } from '@inertiajs/vue3';

export default {
    name: 'Ingenieros',
    components: {
        AuthenticatedLayout,
        Head,
        AgentsTable,
    },
    props: {
        agents: {
            type: Array,
            required: true,
        },
    },
    data() {
        return {};
    },
};
</script>