<template>
    <Head title="Nuevo ingeniero" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Nuevo ingeniero
            </h2>
        </template>
        <div class="container py-7">
            <div class="card">
                <div class="w-6/12">
                    <header>
                        <h2 class="text-lg font-medium text-gray-900">
                            Crear nuevo ingeniero
                        </h2>

                        <p class="mt-1 text-sm text-gray-600">
                            Ingresa los datos del nuevo ingeniero.
                        </p>
                    </header>
                    <form action="" class="space-y-6 mt-7" @submit.prevent="submit">
                        <h4
                            class="font-semibold text-lg text-gray-800 leading-tight"
                        >
                            Datos personales
                        </h4>
                        <!-- Nombre completo -->
                        <div>
                            <InputLabel
                                label="Nombre completo"
                                value="Nombre completo"
                            />
                            <TextInput v-model="agent.name" />
                            <InputError class="mt-2" :message="errors.name" />
                        </div>
                        <!-- Correo electrónico -->
                        <div>
                            <InputLabel
                                label="Correo electrónico"
                                value="Email"
                            />
                            <TextInput v-model="agent.email" type="email" />
                            <InputError class="mt-2" :message="errors.email" />
                        </div>
                        <!-- Departamento -->
                        <div>
                            <InputLabel
                                label="Departamento"
                                value="Departamento"
                            />
                            <select
                                id="department"
                                class="form-control"
                                v-model="agent.department"
                            >
                                <option disabled selected value="">
                                    Selecciona un departamento
                                </option>
                                <option
                                    v-for="department in departments"
                                    :key="department.id"
                                    :value="department.id">
                                    {{ department.name }}
                                </option>
                            </select>
                            <InputError
                                class="mt-2"
                                :message="errors.department"
                            />
                        </div>
                        <!-- Telefono y movil -->
                        <div class="flex items-start space-x-6">
                            <div class="w-1/2">
                                <InputLabel label="Teléfono" value="Teléfono" />
                                <TextInput v-model="agent.phone" type="tel" />
                                <InputError
                                    class="mt-2"
                                    :message="errors.phone"
                                />
                            </div>
                            <div class="w-1/2">
                                <InputLabel label="Móvil" value="Móvil" />
                                <TextInput v-model="agent.mobile" type="tel" />
                                <InputError
                                    class="mt-2"
                                    :message="errors.mobile"
                                />
                            </div>
                        </div>
                        <!-- Puesto laboral -->
                        <div>
                            <InputLabel
                                label="Puesto laboral"
                                value="Puesto laboral"
                            />
                            <TextInput v-model="agent.job_title" />
                            <InputError
                                class="mt-2"
                                :message="errors.job_title"
                            />
                        </div>

                        <!-- Permisos -->
                        <h4
                            class="font-semibold text-lg text-gray-800 leading-tight"
                        >
                            Permisos
                        </h4>

                        <div>
                            <InputLabel label="Permisos" value="Permisos" />
                            <select
                                id="permissions"
                                class="form-control"
                                v-model="agent.permissions"
                            >
                                <option selected disabled value="">Selecciona un permiso</option>
                                <option v-for="permission in permissions" :key="permission.id" :value="permission.id">
                                    {{ permission.name }}
                                </option>
                            </select>
                            <InputError
                                class="mt-2"
                                :message="errors.permissions"
                            />
                        </div>

                        <!-- Contraseña -->
                        <h4
                            class="font-semibold text-lg text-gray-800 leading-tight"
                        >
                            Seguridad
                        </h4>

                        <div class="flex items-start space-x-6">
                            <div class="w-1/2">
                                <InputLabel
                                    label="Contraseña"
                                    value="Contraseña"
                                />
                                <TextInput v-model="agent.password" type="password" />
                                <InputError
                                    class="mt-2"
                                    :message="errors.password"
                                />
                            </div>
                            <div class="w-1/2">
                                <InputLabel
                                    label="Confirmar contraseña"
                                    value="Confirmar contraseña"
                                />
                                <TextInput
                                    v-model="agent.password_confirmation" type="password"
                                />
                                <InputError
                                    class="mt-2"
                                    :message="errors.password_confirmation"
                                />
                            </div>
                        </div>

                        <!-- boton de submit -->
                        <div>
                            <PrimaryButton
                                type="submit"
                                @click.prevent="submit"
                            >
                                <span
                                    v-if="submiting"
                                    class="flex items-center space-x-2"
                                >
                                    <i class="fas fa-spinner fa-spin"></i>
                                    <span> Un momento... </span>
                                </span>
                                <span v-else>Crear</span>
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

export default {
    name: "CreateAgent",
    props: {
        permissions: {
            type: Array,
            required: true,
        },
        departments: {
            type: Array,
            required: true,
        },
    },
    components: {
        AuthenticatedLayout,
        Head,
        InputLabel,
        TextInput,
        InputError,
        PrimaryButton,
    },
    data() {
        return {
            submiting: false,
            agent: {
                name: "",
                email: "",
                department: "",
                phone: "",
                mobile: "",
                job_title: "",
                permissions: "",
                password: "",
                password_confirmation: "",
            },
            errors: {},
        };
    },
    mounted() {},
    methods: {
        submit() {
            let t = this;
            let route = "/dashboard/agents";
            t.submiting = true;
            t.$inertia.post(route, t.agent, {
                preserveScroll: true,
                onSuccess: () => {
                    t.submiting = false;
                    t.resetForm();
                },
                onError: (errors) => {
                    t.submiting = false;
                    t.errors = errors;
                },
            });
        },

        resetForm() {
            this.agent = {
                name: "",
                email: "",
                department: "",
                phone: "",
                mobile: "",
                job_title: "",
                permissions: "",
                password: "",
                password_confirmation: "",
            };
        },
    },
};
</script>
