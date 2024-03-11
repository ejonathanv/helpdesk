<template>
    <!-- Detalles generales -->
    <TicketUpdateDetails 
        :ticket="ticket" 
        :attachments="attachments" 
        :categories="categories" 
        :type="type" />

    <!-- Contacto del ticket -->
    <TicketUpdateContact 
        :ticket="ticket" 
        :contacts="contacts" 
        v-if="type === 'internal'" />

    <!-- Ingeniero asignado -->
    <TicketUpdateAgent 
        :ticket="ticket" 
        :agents="agents" 
        v-if="type === 'internal'" />
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
        type: {
            type: String,
            default: "internal"
        },
        ticket: {
            type: Object,
            required: true,
        },
        attachments: {
            type: Array,
            required: false,
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
