<template>
    <div class="w-1/2">
        <div class="flex items-center justify-between mb-7">
            <header>
                <h2 class="text-lg font-medium text-gray-900">
                    Crear nuevo ticket
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    Llena los siguientes campos para crear un nuevo ticket.
                </p>
            </header>
        </div>

        <form action="" class="space-y-6" @submit.prevent="submit">
            <div>
                <InputLabel for="subject" value="Asunto" />
                <TextInput
                    id="subject"
                    type="text"
                    class="mt-1 block w-full"
                    required
                    autofocus
                    autocomplete="subject"
                    v-model="form.subject"
                />
                <InputError class="mt-2" :message="errors.subject" />
            </div>

            <div>
                <InputLabel for="content" value="Descripción" />
                <TextInput
                    id="description"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.content"
                />
                <InputError class="mt-2" :message="errors.description" />
            </div>

            <div>
                <InputLabel for="account" value="Cuenta" />
                <select
                    id="account"
                    class="form-control"
                    v-model="form.account"
                    @change="e => form.account_name = e.target.options[e.target.selectedIndex].text"
                >
                    <option value="">Selecciona una cuenta</option>
                    <option
                        v-for="account in accounts"
                        :key="account.id"
                        :value="account.id"
                    >
                        {{ account.name }}
                    </option>
                </select>
                <InputError class="mt-2" :message="errors.account" />
            </div>

            <div>
                <InputLabel for="attachment" value="Archivo adjunto" />
                <input
                    id="attachment"
                    type="file"
                    class="mt-1 block w-full"
                    @change="e => form.attachment = e.target.files[0]"
                />
                <InputError class="mt-2" :message="errors.attachment" />
            </div>

            <div>
                <PrimaryButton type="submit">
                    <span v-if="submiting" class="flex items-center space-x-2">
                        <i class="fas fa-spinner fa-spin"></i>
                        <span>
                            Creando ticket...
                        </span>
                    </span>
                    <span v-else>Enviar</span>
                </PrimaryButton>
            </div>
        </form>
    </div>
</template>

<script>
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";

export default {
    name: "NewTicket",
    props: {
        type: {
            type: String,
            default: "internal"
        },
        accounts: {
            type: Array,
            required: true,
        },
    },
    components: {
        PrimaryButton,
        InputLabel,
        TextInput,
        InputError,
    },
    data() {
        return {
            submiting: false,
            errors: {},
            form: {
                subject: "",
                content: "",
                account: "",
                account_name: "",
                attachment: "",
            },
        };
    },
    methods: {
        submit() {

            let route = null;

            if (this.type === "internal") {
                route = "/dashboard/tickets";
            } else if (this.type === "guest") {
                route = "/guest/new-ticket";
            }

            this.submiting = true;
            
            this.$inertia.post(route, this.form, {
                preserveScroll: true,
                errorBag: "createTicket",
                onSuccess: () => {
                    this.form = {
                        subject: "",
                        description: "",
                        account: "",
                    };

                    this.submiting = false;
                    console.log("Ticket created");
                },
                onError: (errors) => {
                    this.submiting = false;
                    this.errors = errors.errors;
                },
            });
        }
    },
};
</script>
