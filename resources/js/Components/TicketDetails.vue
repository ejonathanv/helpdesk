<template>
    <!-- Detalles generales -->
    <TicketUpdateDetails :ticket="ticket" :categories="categories" />

    <!-- Contacto del ticket -->
    <TicketUpdateContact :ticket="ticket" :contacts="contacts" />

    <!-- Ingeniero asignado -->
    <TicketUpdateAgent :ticket="ticket" :agents="agents" />
</template>

<script>

import TicketUpdateDetails from "@/Components/TicketUpdateDetails.vue";
import TicketUpdateContact from "@/Components/TicketUpdateContact.vue";
import TicketUpdateAgent from "@/Components/TicketUpdateAgent.vue";

export default {
    name: "TicketDetails",
    components: {
        TicketUpdateDetails,
        TicketUpdateContact,
        TicketUpdateAgent,
    },
    props: {
        ticket: {
            type: Object,
            required: true,
        },
        agents: {
            type: Array,
            required: true,
        },
        account: {
            type: Object,
            required: true,
        },
        contacts: {
            type: Array,
            required: true,
        },
        categories: {
            type: Array,
            required: true,
        },
    }
};
</script>
