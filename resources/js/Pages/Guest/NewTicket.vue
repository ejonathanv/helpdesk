<template>
    <Head title="Crear nuevo ticket" />
    <GuestAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Nuevo ticket</h2>
        </template>
        <div class="container py-7">
            <div class="card">
                <!-- Formulario de creación de tickets -->
                <TicketForm :accounts="accounts" type="guest" />
            </div>
        </div>
    </GuestAuthenticatedLayout>
</template>

<script>
import GuestAuthenticatedLayout from '@/Layouts/GuestAuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import TicketForm from '@/Components/TicketForm.vue';

export default {
    name: 'GuestNewTicket',
    props: {
        accounts: {
            type: Array,
            required: true,
        },
    },
    components: {
        GuestAuthenticatedLayout,
        Head,
        TicketForm,
    },
    data() {
        return {};
    },
};
</script>