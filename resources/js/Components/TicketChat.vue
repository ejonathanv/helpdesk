<template>
    <div class="card">
        <div class="flex items-center justify-between mb-7">
            <header>
                <h2 class="text-lg font-medium text-gray-900">
                    Chat del ticket
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    <span v-if="type === 'internal'">
                        Aquí puedes ver la conversación con el cliente.
                    </span>
                    <span v-else>
                        Aquí puedes ver la conversación con el ingeniero
                        asignado.
                    </span>
                </p>
            </header>
        </div>

        <div v-if="contactIsAssigned && agentIsAssigned">
            <div class="chatSection" id="chatSection">
                <div v-for="msg in chat" class="chatMsg">
                    <p class="font-bold text-sm">{{ msg.from }}:</p>
                    <p class="text-gray-600 mb-2">
                        {{ msg.message }}
                    </p>
                    <p class="text-gray-400 text-xs font-bold">
                        {{ msg.created_at }}
                    </p>
                </div>
            </div>

            <div class="newMsg mt-4">
                <form
                    action=""
                    class="flex items-stretch space-x-2"
                    @submit.prevent="submit"
                >
                    <input
                        type="text"
                        class="form-control"
                        placeholder="Escribe un mensaje..."
                        required
                        v-model="message"
                        ref="messageInput"
                    />
                    <PrimaryButton type="submit">
                        <i class="fas fa-paper-plane mr-2"></i>
                        <span>Enviar</span>
                    </PrimaryButton>
                </form>
            </div>
        </div>

        <div v-else>
            <p class="text-gray-600 font-bold border-l-2 border-dynacom-red p-4">
                Para iniciar el chat, primero debes asignar un ingeniero y un contacto al ticket.
            </p>
        </div>
    </div>
</template>

<script>
import PrimaryButton from "@/Components/PrimaryButton.vue";

export default {
    name: "TicketChat",
    props: {
        type: {
            type: String,
            default: "internal",
        },
        ticket: {
            type: Object,
            required: true,
        },
        chat: {
            type: Object,
            required: true,
        },
    },
    components: {
        PrimaryButton,
    },
    data() {
        return {
            message: "",
            contactIsAssigned: this.ticket.contact_email,
            agentIsAssigned: this.ticket.agent_id,
        };
    },
    methods: {
        submit() {
            let t = this;
            let route = t.getRoute();
            let data = {
                message: t.message,
                type: t.type,
            };

            t.$inertia.post(route, data, {
                preserveScroll: true,
                preserveState: true,
                onSuccess: () => {
                    // Necesitamos actualizar el chat con la función getChat() del componente padre
                    t.$emit("update");
                    t.message = "";

                    // Enfocamos el input de nuevo
                    t.$refs.messageInput.focus();
                },
                onError: (errors) => {
                    console.log(errors);
                },
            });
        },
        getRoute() {
            let t = this;
            if (t.type == "internal") {
                let route = "/dashboard/tickets/" + t.ticket.id + "/messages";
                return route;
            } else {
                let route = "/guest/tickets/" + t.ticket.id + "/messages";
                return route;
            }
        },
    },
};
</script>

<style scoped>
.chatSection {
    height: auto;
    overflow-y: scroll;
}

.chatMsg {
    margin-bottom: 10px;
    background: #fff;
    padding: 10px;
    border-radius: 5px;
}

.chatSection .chatMsg:nth-child(odd) {
    background: #eeeeee;
}
.chatSection .chatMsg:nth-child(even) {
    background: #f5f5f5;
}
</style>
