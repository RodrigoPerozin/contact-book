<script setup lang="ts">
  import { toRef } from 'vue';
  import type {Paginator} from '../assets/js/interfaces'
  const props = defineProps<{
    paginator: Paginator | null,
    goToPreviousPage: () => void,
    goToNextPage: () => void,
  }>()

  const paginator = toRef(props, 'paginator');
</script>

<template>
    <div name="page-navigator" class="d-flex flex-wrap w-100 justify-content-end me-3" v-show="paginator">
        <nav aria-label="Page navigation example">
          <ul class="pagination">
            <li class="page-item">
              <a class="page-link mouse-pointer" @click="props.goToPreviousPage" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
              </a>
            </li>
            <li class="page-item"><a class="page-link mouse-pointer" @click="props.goToPreviousPage">{{ (paginator?.current_page === 1) ? ((paginator?.current_page) ? (paginator?.last_page) : '-') : ((paginator?.current_page) ? (paginator?.current_page-1) : '-')}}</a></li>
            <li class="page-item"><a class="page-link mouse-pointer" >{{ (paginator?.current_page) ? paginator?.current_page : '-'}}</a></li>
            <li class="page-item"><a class="page-link mouse-pointer" @click="props.goToNextPage">{{ (paginator?.current_page) ? (((paginator?.current_page+1) > paginator.last_page) ? 1 : (paginator?.current_page+1)) : '-'}}</a></li>
            <li class="page-item">
              <a class="page-link mouse-pointer" @click="props.goToNextPage" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
              </a>
            </li>
          </ul>
        </nav>
      </div>
</template>

<style>
  .mouse-pointer{
    cursor: pointer;
  }
</style>