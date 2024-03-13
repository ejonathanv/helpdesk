<template>
    <Head title="Tickets" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tickets</h2>
        </template>
        <div class="container full py-7">
            <div class="card">
                <TicketsTable :tickets="tickets"
                    :departments="departments"
                    :agents="agents"
                    :statuses="statuses"
                    :severities="severities"
                    :priorities="priorities"
                    :categories="categories" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import TicketsTable from '@/Components/TicketsTable.vue';
import { Head } from '@inertiajs/vue3';

export default {
    name: 'Tickets',
    components: {
        AuthenticatedLayout,
        Head,
        TicketsTable,
    },
    props: {
        tickets: {
            type: Array,
            required: true,
        },
        departments: {
            type: Array,
            required: true,
        },
        agents: {
            type: Array,
            required: true,
        },
        statuses: {
            type: Array,
            required: true,
        },
        severities: {
            type: Array,
            required: true,
        },
        priorities: {
            type: Array,
            required: true,
        },
        categories: {
            type: Array,
            required: true,
        },
    },
    data() {
        return {};
    },
};
</script>