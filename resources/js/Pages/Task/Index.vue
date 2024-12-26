<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Head, Link, useForm} from '@inertiajs/vue3';
import {ref} from "vue";
import {useToast} from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-sugar.css';
import Modal from "@/Components/Modal.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import DangerButton from "@/Components/DangerButton.vue";
import NoDataFound from "@/Components/NoDataFound.vue";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {faEdit, faPlus, faTrashCan} from "@fortawesome/free-solid-svg-icons";
import InputLabel from "@/Components/InputLabel.vue";


const $toast = useToast();

const props = defineProps({
    tasks: Object,
    active: String,
    inactive: String,
    taskStatus: Object,
})

const form = useForm({});

const shouldDelete = ref(false);

const taskID = ref(null);

const selectForDeletion = (id) => {
    shouldDelete.value = true;
    taskID.value = id;
}

const closeModal = () => {
    shouldDelete.value = false;
};

const deleteTask = () => {
    form.delete(route('tasks.destroy', taskID.value), {
        preserveScroll: true,
        onSuccess: () => {
            $toast.success("Task deleted successfully!");
            closeModal();
            taskID.value = 0;
        },
        onError: () => {
            $toast.error("Failed to deleted task!");
        },
    });
};

const formFilter = useForm({
    status: 'all',
    featured: 'all'
});

const filterHandle = () => {
    let tempUrl = new URL(window.location.href);
    let url = `${route('tasks.index')}?status=${formFilter.status}`;

    setTimeout(() => {
        form.get(url, {
            preserveState: true,
            onError: () => {
                $toast.error("Failed to retrieve data!");
            },
        });
    });
}

</script>

<template>
    <Head>
        <title>Tasks</title>
    </Head>

    <AuthenticatedLayout>

        <div class="px-5 bg-white pt-5 h-screen">
            <div class="col-span-2 lg:col-span-2 flex flex-col gap-3 card-shadow mb-5">
                <div class="flex flex-row justify-end pb-6">

                    <Link :href="route('tasks.create')"
                          class="text-white px-3 py-2 bg-sky-500 rounded hover:opacity-90 capitalize font-medium text-sm focus:outline-none flex space-x-2 items-center">
                        <font-awesome-icon :icon="faPlus"/>
                        <span> Add task</span>
                    </Link>
                </div>
                <div v-if="props.tasks.length >0" class="flex flex-row justify-end gap-4">
                    <div>
                        <form>
                            <InputLabel class="text-left text-black">Filter by status</InputLabel>
                            <select @change="filterHandle" v-model="formFilter.status"
                                    class="bg-gray-100 border-0 focus:ring-gray-100 rounded-none capitalize text-14 text-black font-semibold">
                                <option value="all">All</option>
                                <option v-for="(status ,key) in props.taskStatus" :key="key" :value="status">
                                    {{ status }}
                                </option>
                            </select>
                        </form>
                    </div>

                </div>
                <div class="flex flex-row">
                    <div v-if="props.tasks.length" class="w-full">
                        <table class="w-full text-center">
                            <thead class="text-color">
                            <tr>
                                <th class="py-3 rounded-tl-2xl font-barlow w-20">#</th>
                                <th class="py-3 text-16 font-barlow font-semibold text-left">Title</th>
                                <th class="py-3 text-16 font-barlow font-semibold text-center">Description</th>
                                <th class="py-3 text-16 font-barlow font-semibold">Status</th>
                                <th class="py-3 text-16 font-barlow font-semibold">Action</th>
                            </tr>

                            </thead>
                            <tbody>
                            <tr v-for="(task ,key) in props.tasks" :key="key"
                                class="border-b border-t border-gray-200 dark:border-gray-500 text-color group">
                                <td class="py-3 text-14 font-ibmPlex">
                                    {{ (key + 1) }}
                                </td>

                                <td class="text-left">
                                    <span> {{ task.title ?? '' }}</span>
                                </td>
                                <td class="text-center">
                                    <span> {{ task.description ?? '' }}</span>
                                </td>

                                <td class="py-3 text-14 font-ibmPlex">
                                    <span v-if="task.status"
                                              class="px-3 py-1 rounded-[15px] text-12 text-black font-semibold capitalize">{{
                                                task.status
                                            }}</span>
                                </td>

                                <td>
                                    <div class="flex flex-row gap-2 justify-center">
                                        <Link :href="route('tasks.edit',task.id)" type="button"
                                              class="self-center">
                                            <font-awesome-icon :icon="faEdit"
                                                               class="text-indigo-600 hover:text-orange pr-2"/>
                                        </Link>
                                        <button @click="selectForDeletion(task.id)" type="button"
                                                class="self-center">
                                            <font-awesome-icon :icon="faTrashCan"
                                                               class="text-red-400 hover:text-red-600"/>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <NoDataFound v-else/>
                </div>

                <div class="flex flex-col">

                </div>
            </div>
        </div>

        <Modal :show="shouldDelete" @close="closeModal">
            <div class="p-6 text-center">
                <h2 class="text-lg font-medium text-gray-900">
                    Are you sure you want to delete this task?
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    Once your task is deleted, all of its resources and data will be permanently deleted.
                </p>


                <div class="mt-6 flex justify-center">
                    <SecondaryButton @click="closeModal"> Cancel</SecondaryButton>

                    <DangerButton
                        class="ms-3"
                        @click="deleteTask"
                    >
                        Delete Task
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>

</template>

<style scoped>

</style>
