<template>
    <div class="w-1/2">
        <div class="flex items-center justify-between mb-7">
            <header>
                <h2 class="text-lg font-medium text-gray-900">
                    Crear nuevo departamento
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    Llena los siguientes campos para crear un nuevo departamento.
                </p>
            </header>
        </div>

        <form action="" class="space-y-6" @submit.prevent="submit">
            <div>
                <InputLabel for="name" value="Nombre" />
                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    required
                    autofocus
                    autocomplete="name"
                    v-model="form.name"
                />
                <InputError class="mt-2" :message="$page.props.errors.name" />
            </div>

            <div>
                <InputLabel for="description" value="Descripción" />
                <TextInput
                    id="description"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.description"
                />
                <InputError class="mt-2" :message="$page.props.errors.description" />
            </div>

            <div>
                <PrimaryButton :submiting="submiting">
                    <span v-if="submiting" class="flex items-center space-x-2">
                        <i class="fas fa-spinner fa-spin"></i>
                        <span>
                            Un momento...
                        </span>
                    </span>
                    <span v-else>
                        Crear departamento
                    </span>     
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
    name: "NewDepartment",
    components: {
        PrimaryButton,
        InputLabel,
        TextInput,
        InputError,
    },
    data() {
        return {
            submiting: false,
            form: {
                name: "",
                description: "",
            },
        };
    },
    methods: {
        submit(){
            const t = this;
            const route = '/dashboard/config/departments';

            t.submiting = true;

            t.$inertia.post(route, t.form, {
                preserveScroll: true,
                onSuccess: () => {
                    t.submiting = false;
                },
                onError: (errors) => {
                    t.submiting = false;
                    t.errors = errors.errors;
                },
            })
        }
    },
};
</script>
