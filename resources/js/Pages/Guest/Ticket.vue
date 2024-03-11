<template>
    <Head :title="`Ticket ${ticket.number}`" />
    <GuestAuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Ticket {{ ticket.number }}
                </h2>

                <div v-if="ticket.status_id !== 7">
                    <DangerButton
                        type="submit"
                        class="mr-2"
                        @click.prevent="cancelTicket">
                        Cancelar ticket
                    </DangerButton>

                    <Modal
                        title="Cancelar ticket"
                        :show="canceling"
                        @close="canceling = false">

                        <form class="p-4" @submit.prevent="submitCancelTicket">
                            <h4 class="font-semibold text-xl text-gray-800 leading-tight mb-4">
                                Cancelación de ticket
                            </h4>
                            <p class="mb-4">
                                Estas a punto de cancelar el ticket
                                {{ ticket.number }}. Por favor, escribe la razón
                                de la cancelación.
                            </p>

                            <textarea
                                name=""
                                id=""
                                cols="30"
                                rows="4"
                                class="form-control"
                                v-model="reason"
                                placeholder="Escribe la razón de la cancelación"
                            ></textarea>

                            <div class="flex mt-7">
                                <DangerButton> Cancelar ticket </DangerButton>
                                <SecondaryButton
                                    class="ml-2"
                                    @click="canceling = false">
                                    Cerrar
                                </SecondaryButton>
                            </div>
                        </form>

                    </Modal>
                </div>
            </div>
        </template>
        <div class="container py-7 flex items-start space-x-7" v-cloak>
            <div class="w-2/12 flex flex-col space-y-4 sideBarMenu">
                <a href="#details"
                    class="flex items-center space-x-3"
                    :class="{ active: currentTab === 'details' }"
                    @click="tab('details')">
                    <i class="far fa-fw fa-file-alt text-dynacom-red"></i>
                    <span>Detalles del ticket</span>
                </a>
                <a href="#chat"
                    class="flex items-center space-x-3"
                    :class="{ active: currentTab === 'chat' }"
                    @click="tab('chat')">
                    <i class="far fa-fw fa-comment text-dynacom-red"></i>
                    <span>Chat</span>
                </a>
                <a href="#events" class="flex items-center space-x-3" 
                    :class="{ active: currentTab === 'events' }"
                    @click="tab('events')">
                    <i class="far fa-fw fa-calendar text-dynacom-red"></i>
                    <span>Eventos</span>
                </a>
                <a href="#history"
                    class="flex items-center space-x-3"
                    :class="{ active: currentTab === 'history' }"
                    @click="tab('history')">
                    <i class="fa fa-fw fa-history text-dynacom-red"></i>
                    <span>Historial</span>
                </a>
            </div>
            <div class="w-10/12">
                <div class="card mb-6" v-if="agent">
                    <p>
                        <span class="font-semibold leading-tight block mb-4">
                            Tu ticket esta siendo atendido por:
                        </span>
                        <span
                            class="font-semibold text-xl text-gray-800 leading-tight block mb-2"
                        >
                            {{ agent.name }}
                        </span>
                        <span
                            class="font-semibold text-gray-400 leading-tight block mb-2"
                        >
                            {{ agent.email }}
                        </span>
                        <span
                            class="font-semibold text-xs text-gray-400 leading-tight block"
                        >
                            {{ agent.job_title }}
                        </span>
                    </p>
                </div>

                <TicketDetails
                    v-if="currentTab === 'details'"
                    type="guest"
                    :ticket="ticket"
                    :attachments="attachments"
                    :agents="agents"
                    :account="account"
                    :contacts="contacts"
                    :categories="categories"
                />

                <TicketChat
                    v-if="currentTab === 'chat'"
                    :ticket="ticket"
                    :chat="chat"
                    type="guest"
                    @update="getChat"
                />

                <TicketEvents v-if="currentTab === 'events'"
                    :ticket="ticket"
                    :events="events"
                    @update="getEvents"></TicketEvents>

                <TicketHistory
                    v-if="currentTab === 'history'"
                    :ticket="ticket"
                    :histories="histories"
                    @update="getHistories"
                />
            </div>
        </div>
    </GuestAuthenticatedLayout>
</template>

<script>
import GuestAuthenticatedLayout from "@/Layouts/GuestAuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import TicketDetails from "@/Components/TicketDetails.vue";
import TicketChat from "@/Components/TicketChat.vue";
import TicketHistory from "@/Components/TicketHistory.vue";
import TicketEvents from "@/Components/TicketEvents.vue";
import DangerButton from "@/Components/DangerButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import Modal from "@/Components/Modal.vue";

export default {
    name: "ShowTicket",
    props: {
        ticket: {
            type: Object,
            required: true,
            default: () => {},
        },
        attachments: {
            type: Array,
            required: true,
            default: () => [],
        },
        agent: {
            type: Object,
            default: () => {},
        },
        account: {
            type: Object,
            default: () => {},
        },
        contacts: {
            type: Array,
            default: () => [],
        },
    },
    components: {
        GuestAuthenticatedLayout,
        Head,
        TicketDetails,
        TicketChat,
        TicketHistory,
        TicketEvents,
        DangerButton,
        SecondaryButton,
        Modal,
    },
    data() {
        return {
            currentTab: "details",
            chat: [],
            events: [],
            histories: [],
            canceling: false,
            reason: "",
            categories: [],
        };
    },
    mounted() {
        let hash = window.location.hash;
        this.currentTab = hash ? hash.replace("#", "") : "details";
        this.tab(this.currentTab);
    },
    methods: {
        getChat() {
            let t = this;
            let route = "/guest/tickets/" + t.ticket.id + "/messages";
            t.currentTab = "chat";

            fetch(route)
                .then((response) => response.json())
                .then((data) => {
                    t.chat = data.messages;
                    setTimeout(function () {
                        let chatSection =
                            document.getElementById("chatSection");

                        chatSection.scrollTo({
                            top: chatSection.scrollHeight,
                            behavior: "smooth",
                        });
                    }, 300);
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        getEvents(){
            let t = this;
            let route = '/guest/tickets/' + t.ticket.id + '/events';
            t.currentTab = "events";

            fetch(route)
                .then(response => response.json())
                .then(data => {
                    t.events = data;
                })
                .catch(error => {
                    console.log(error);
                });
        },
        getHistories() {
            let t = this;
            let route = "/guest/tickets/" + t.ticket.id + "/histories";
            t.currentTab = "history";

            fetch(route)
                .then((response) => response.json())
                .then((data) => {
                    t.histories = data;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        tab(tab) {
            this.currentTab = tab;

            if (tab == "chat") {
                this.getChat();
            } else if (tab == "events") {
                this.getEvents();
            } else if (tab == "history") {
                this.getHistories();
            }
        },
        cancelTicket() {
            // Necesitamos abrir un modal para confirmar la cancelación del ticket y explicar el motivo de la cancelación.
            let t = this;
            t.canceling = true;
        },
        submitCancelTicket() {
            // Necesitamos crear los datos que vamos a enviar al servidor para cancelar el ticket.
            let t = this;
            let data = {
                ticket_id: t.ticket.id,
                reason: t.reason,
                _method: "DELETE",
            };

            // Necesitamos la ruta para cancelar el ticket.
            let route = '/guest/tickets/' + t.ticket.id;

            // Vamos a enviar los datos al servidor con inertia.
            t.$inertia.post(route, data, {
                preserveScroll: true,
                onSuccess: () => {
                    t.canceling = false;
                },
            });
        },
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
