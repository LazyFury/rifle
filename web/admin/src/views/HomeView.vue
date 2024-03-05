<script setup>
import { useRoute } from 'vue-router';
import TheWelcome from '../components/TheWelcome.vue'
import {Chart} from '@antv/g2'
import { onMounted } from 'vue';
const route = useRoute()

onMounted(()=>{
  const chart = new Chart({ container: "container" });
  chart.options({
    type: "interval",
    autoFit: true,
    data: [
      { year: "1951 年", sales: 38 },
      { year: "1952 年", sales: 52 },
      { year: "1956 年", sales: 61 },
      { year: "1957 年", sales: 145 },
      { year: "1958 年", sales: 48 },
      { year: "1959 年", sales: 38 },
      { year: "1960 年", sales: 38 },
      { year: "1962 年", sales: 38 },
    ],
    encode: { x: "year", y: "sales" },
  });

  chart.render();
})
</script>

<template>
  <main>
    <div class="flex flex-row gap-2 flex-wrap">
      <div class="flex-1">
        <TheWelcome />
        <ElCard shadow="never" class="mt-2">
          {{ route.meta }}
          <div id="container" class="w-320px h-240px" style="z-index: 1;"></div>

        </ElCard>
      </div>
      <div class="xl:w-240px sm:w-full flex flex-col gap-2">
        <ElCard class="min-h-100" shadow="never">
          hello
        </ElCard>
        <ElCard shadow="never">
          Contact
        </ElCard>
      </div>
    </div>
  </main>
</template>
