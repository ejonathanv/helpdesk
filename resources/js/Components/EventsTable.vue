<template>
    <div class="card">
        <!-- Alerta de evento creado -->
        <div v-if="$page.props.flash.newEventCreated" class="alert">
            <p class="flashMsg">
                {{ $page.props.flash.newEventCreated }}
            </p>
        </div>

        <!-- Alerta de evento eliminado -->
        <div v-if="$page.props.flash.eventDeleted" class="alert">
            <p class="flashMsg">
                {{ $page.props.flash.eventDeleted }}
            </p>
        </div>

        <!-- Encabezado y boton para crear nuevo evento -->
        <div class="flex items-center justify-between mb-7">
            <header>
                <h2 class="text-lg font-medium text-gray-900">
                    Eventos del ticket
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    Aquí puedes ver los eventos del ticket.
                </p>
            </header>
            <div v-if="user">
                <PrimaryButton @click.prevent="showModal = true">
                    <i class="fas fa-plus mr-2"></i>
                    <span>Agregar evento</span>
                </PrimaryButton>
            </div>
        </div>

        <!-- Si hay eventos se muestra la tabla de eventos -->
        <div class="table-responsive" v-if="events.length">
            <table class="table bordered vtop">
                <thead>
                    <tr>
                        <th width="40%">Evento</th>
                        <th class="text-center" width="20%" v-if="user">Visibilidad</th>
                        <th class="text-center" width="20%" v-if="user">Tipo</th>
                        <th class="text-center" width="20%">Tiempo invertido</th>
                        <th v-if="user">Adjuntos</th>
                        <th class="text-center" v-if="user"><span>Acciones</span></th>
                    </tr>
                </thead>

                <tbody>
                    <tr style="vertical-align: top"
                        v-for="event in events"
                        :key="event.id">
                        <td>
                            <p class="font-bold mb-2">
                                {{ event.comments }}
                            </p>
                            <p class="text-gray-600">
                                {{ event.by }}
                            </p>
                            <p class="text-gray-400 text-xs font-bold">
                                {{ event.created_at }}
                            </p>
                        </td>
                        <td class="text-center" v-if="user" v-html="event.public_as_badge"></td>
                        <td class="text-center" v-if="user" v-html="event.type_badge"></td>
                        <td class="text-center">{{ event.total_time_formatted }}</td>
                        <td v-if="user">
                            <div class="flex items-center justify-center flex-no-wrap space-x-2" v-if="event.attachments.length">
                                <a class="text-gray-600 text-2xl"
                                    v-for="attachment in event.attachments"
                                    :title="attachment.original_file_name"
                                    target="_blank"
                                    :href="attachment.file_path">
                                    <i v-if="attachment.file_ext == 'pdf'" class="fas fa-file-pdf fa-lg"></i>
                                    <i v-else-if="attachment.file_ext == 'doc' || attachment.file_ext == 'docx'" class="fas fa-file-word fa-lg"></i>
                                    <i v-else-if="attachment.file_ext == 'xls' || attachment.file_ext == 'xlsx'" class="fas fa-file-excel fa-lg"></i>
                                    <i v-else-if="attachment.file_ext == 'ppt' || attachment.file_ext == 'pptx'" class="fas fa-file-powerpoint fa-lg"></i>
                                    <i v-else-if="attachment.file_ext == 'jpg' || attachment.file_ext == 'jpeg' || attachment.file_ext == 'png' || attachment.file_ext == 'gif'" class="fas fa-file-image fa-lg"></i>
                                    <i v-else-if="attachment.file_ext == 'zip' || attachment.file_ext == 'rar'" class="fas fa-file-archive fa-lg"></i>
                                    <i v-else class="fas fa-file fa-lg"></i>
                                </a>
                            </div>
                            <div v-else>
                                <p class="text-gray-300 text-center">
                                    <i class="fas fa-times fa-lg"></i>
                                </p>
                            </div>  
                        </td>
                        <td class="text-center" v-if="user">
                            <form action="" @submit.prevent="deleteEvent(event.id)">
                                <button type="submit">
                                    <i class="fas fa-trash-alt text-red-500"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- En caso de que no haya eventos se muestra un mensaje -->
        <div v-else>
            <p class="text-gray-500 text-center my-6">
                No hay eventos registrados para este ticket.
            </p>
        </div>

        <template v-if="user">
            <!-- Modal para crear un nuevo evento -->
            <div class="modal sm" v-if="showModal">
                <div class="modalBody overflow-hidden">
                    <div class="flex items-center justify-between mb-7">
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">
                                Crear nuevo evento
                            </h2>

                            <p class="mt-1 text-sm text-gray-600">
                                En esta sección puedes crear un nuevo evento para el
                                ticket.
                            </p>
                        </header>

                        <a href="#" @click.prevent="toggleModal">
                            <i
                                class="fas fa-times fa-lg text-gray-600 hover:text-gray-700"
                            ></i>
                        </a>
                    </div>

                    <form
                        action=""
                        class="space-y-4"
                        @submit.prevent="submit"
                        enctype="multipart/form-data"
                    >
                        <h5 class="font-bold text-dynacom-red">
                            Comentarios ó descripción del evento
                        </h5>
                        <div>
                            <InputLabel for="event" value="Comentarios" />
                            <textarea
                                name="comments"
                                id="event"
                                cols="30"
                                rows="3"
                                class="form-control"
                                v-model="form.comments"
                                placeholder="Escribe tus comentariós o descripción sobre este evento"
                                required
                            ></textarea>
                        </div>

                        <h5 class="font-bold text-dynacom-red">Tiempo invertido</h5>
                        <div class="flex items-start space-x-4">
                            <div class="w-1/3">
                                <InputLabel for="days" value="Días" />
                                <TextInput
                                    type="number"
                                    name="days"
                                    id="days"
                                    class="form-control"
                                    placeholder="0"
                                    v-model="form.days"
                                    required
                                    min="0"
                                >
                                </TextInput>
                            </div>

                            <div class="w-1/3">
                                <InputLabel for="hours" value="Horas" />
                                <TextInput
                                    type="number"
                                    name="hours"
                                    id="hours"
                                    class="form-control"
                                    placeholder="0"
                                    v-model="form.hours"
                                    required
                                    min="0"
                                >
                                </TextInput>
                            </div>

                            <div class="w-1/3">
                                <InputLabel for="minutes" value="Minutos" />
                                <TextInput
                                    type="number"
                                    name="minutes"
                                    id="minutes"
                                    class="form-control"
                                    placeholder="0"
                                    v-model="form.minutes"
                                    required
                                    min="0"
                                >
                                </TextInput>
                            </div>
                        </div>

                        <h5 class="font-bold text-dynacom-red">Atención</h5>

                        <div class="flex items-start space-x-4">
                            <div class="w-1/2">
                                <InputLabel for="type" value="Tipo de atención" />
                                <select
                                    name="type"
                                    id="type"
                                    class="form-control"
                                    v-model="form.type"
                                    required
                                >
                                    <option value="remote">Atención remota</option>
                                    <option value="on-site">
                                        Atención en sitio
                                    </option>
                                </select>
                            </div>
                            <div class="w-1/2">
                                <InputLabel for="publicAs" value="Publicar como" />
                                <select
                                    name="publicAs"
                                    id="publicAs"
                                    class="form-control"
                                    v-model="form.publicAs"
                                    required
                                >
                                    <option value="public">Público</option>
                                    <option value="private">Privado</option>
                                </select>
                            </div>
                        </div>

                        <h5 class="font-bold text-dynacom-red">
                            Archivos adjuntos
                        </h5>

                        <div>
                            <InputLabel for="files" value="Archivos" />
                            <input
                                type="file"
                                name="files"
                                id="files"
                                multiple
                                ref="files"
                                @change="handleFileUpload"
                            />
                        </div>

                        <div class="pt-6">
                            <PrimaryButton type="submit">
                                <span
                                    v-if="submiting"
                                    class="flex items-center space-x-2"
                                >
                                    <i class="fas fa-spinner fa-spin"></i>
                                    <span> Un momento... </span>
                                </span>
                                <span v-else>Publicar evento</span>
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </template>
    </div>
</template>

<script>
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";

export default {
    name: "EventsTable",
    components: {
        PrimaryButton,
        InputLabel,
        TextInput,
    },
    props: {
        ticket: {
            type: Object,
            required: true,
        },
        events: {
            type: Array,
            required: true,
        },
    },
    data() {
        return {
            user: this.$page.props.auth.user,
            submiting: false,
            showModal: false,
            form: {
                comments: "",
                days: 0,
                hours: 0,
                minutes: 0,
                type: "on-site",
                publicAs: "public",
                files: "",
            },
        };
    },
    methods: {
        toggleModal() {
            this.showModal = !this.showModal;
            this.resetForm();
        },
        handleFileUpload() {
            this.form.files = this.$refs.files.files;
        },
        submit() {
            const t = this;
            t.submiting = true;
            t.$inertia.post(
                "/dashboard/tickets/" + t.ticket.id + "/events",
                t.form,
                {
                    preserveScroll: true,
                    preserveState: true,
                    onSuccess: () => {
                        t.submiting = false;
                        t.toggleModal();
                        t.$parent.$emit("update");
                        t.resetForm();
                    },
                    onError: (errors) => {
                        t.submiting = false;
                        console.log(errors);
                    },
                }
            );
        },
        resetForm() {
            this.form = {
                comments: "",
                days: 0,
                hours: 0,
                minutes: 0,
                type: "on-site",
                publicAs: "public",
                files: "",
            };
        },
        deleteEvent(id) {
            if (confirm("¿Estás seguro de eliminar este evento?")) {
                this.$inertia.delete(
                    "/dashboard/tickets/" + this.ticket.id + "/events/" + id,
                    {
                        preserveScroll: true,
                        preserveState: true,
                        onSuccess: () => {
                            this.$parent.$emit("update");
                        },
                        onError: (errors) => {
                            console.log(errors);
                        },
                    }
                );
            }
        },
    },
};
</script>
