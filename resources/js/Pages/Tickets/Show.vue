<template>
    <Head :title="`Ticket ${ticket.number}`" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Ticket {{ ticket.number }}
            </h2>
        </template>
        <div class="container py-7 flex items-start space-x-7">
            <div class="w-2/12 flex flex-col space-y-4 sideBarMenu">
                <a href="#" class="flex items-center space-x-3" 
                    :class="{ active: currentTab === 'details' }"
                    @click.prevent="currentTab = 'details'">
                    <i class="far fa-fw fa-file-alt text-dynacom-red"></i>
                    <span>Detalles del ticket</span>
                </a>
                <a href="#" class="flex items-center space-x-3" 
                    :class="{ active: currentTab === 'chat' }"
                    @click.prevent="currentTab = 'chat'">
                    <i class="far fa-fw fa-comment text-dynacom-red"></i>
                    <span>Chat con el cliente</span>
                </a>
                <a href="#" class="flex items-center space-x-3" 
                    :class="{ active: currentTab === 'events' }"
                    @click.prevent="currentTab = 'events'">
                    <i class="far fa-fw fa-calendar text-dynacom-red"></i>
                    <span>Eventos</span>
                </a>
                <a href="#" class="flex items-center space-x-3" 
                    :class="{ active: currentTab === 'history' }"
                    @click.prevent="currentTab = 'history'">
                    <i class="fa fa-fw fa-history text-dynacom-red"></i>
                    <span>Historial</span>
                </a>
                <a href="#" class="flex items-center space-x-3"
                    :class="{ active: currentTab === 'security' }"
                    @click.prevent="currentTab = 'security'">
                    <i class="fa fa-fw fa-lock text-dynacom-red"></i>
                    <span>Seguridad</span>
                </a>
            </div>
            <div class="w-10/12">
                <TicketDetails v-if="currentTab === 'details'" 
                    :ticket="ticket" 
                    :agents="agents"
                    :account="account"
                    :contacts="contacts"
                    :categories="categories" />
                <TicketChat v-if="currentTab === 'chat'" :ticket="ticket"></TicketChat>
                <TicketEvents v-if="currentTab === 'events'" :ticket="ticket"></TicketEvents>
                <TicketHistory v-if="currentTab === 'history'" :ticket="ticket"></TicketHistory>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import TicketDetails from "@/Components/TicketDetails.vue";
import TicketChat from "@/Components/TicketChat.vue";
import TicketEvents from "@/Components/TicketEvents.vue";
import TicketHistory from "@/Components/TicketHistory.vue";

export default {
    name: "ShowTicket",
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
    },
    components: {
        AuthenticatedLayout,
        Head,
        TicketDetails,
        TicketChat,
        TicketEvents,
        TicketHistory,
    },
    data() {
        return {
            currentTab: "details",
        };
    },
};
</script>

<style scoped>
.sideBarMenu a {
    padding: 7px 10px;
    font-size: 14px;
    border-radius: 5px;
}

.sideBarMenu a:hover {
    background: #fff;
    box-shadow: 0 2px 3px rgba(0, 0, 0, 0.1);
}

.sideBarMenu a.active {
    background: #fff;
    box-shadow: 0 2px 3px rgba(0, 0, 0, 0.1);
    color: #ba2025;
}
</style>
