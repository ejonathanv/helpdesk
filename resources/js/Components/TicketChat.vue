<template>
    <div class="card">
        <div class="flex items-center justify-between mb-7">
            <header>
                <h2 class="text-lg font-medium text-gray-900">
                    Chat del ticket
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    Aquí puedes ver la conversación con el cliente.
                </p>
            </header>
        </div>

        <div class="chatSection" id="chatSection">
            <div v-for="msg in chat" class="chatMsg">
                <p class="font-bold text-sm">
                    {{ msg.from }}:
                </p>
                <p class="text-gray-600 mb-2">
                    {{ msg.message }}
                </p>
                <p class="text-gray-400 text-xs font-bold">
                    {{ msg.created_at }}
                </p>
            </div>
        </div>

        <div class="newMsg mt-4">
            <form action="" class="flex items-stretch space-x-2" @submit.prevent="submit">
                <input type="text" class="form-control" placeholder="Escribe un mensaje..." required v-model="message" ref="messageInput">
                <PrimaryButton type="submit">
                    <i class="fas fa-paper-plane mr-2"></i>
                    <span>Enviar</span>
                </PrimaryButton>
            </form>
        </div>
    </div>
</template>

<script>

import PrimaryButton from "@/Components/PrimaryButton.vue";

export default {
    name: "TicketChat",
    props: {
        ticket: {
            type: Object,
            required: true,
        },
        chat: {
            type: Object,
            required: true,
        }
    },
    components: {
        PrimaryButton,
    },
    data() {
        return {
            message: "",
        };
    },
    methods: {
        submit() {
            let t = this;
            let route = "/dashboard/tickets/" + t.ticket.id + "/messages";
            let data = {
                message: t.message,
            };

            t.$inertia.post(route, data, {
                preserveScroll: true,
                preserveState: true,
                onSuccess: () => {
                    console.log("Message sent");
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
    }
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