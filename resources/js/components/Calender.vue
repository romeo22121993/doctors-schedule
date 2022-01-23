<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <FullCalendar :options="calendarOptions" />
            </div>
        </div>
    </div>
</template>
<script>
    import FullCalendar from '@fullcalendar/vue'
    import dayGridPlugin from '@fullcalendar/daygrid'
    export default {
  components: {
    FullCalendar // make the <FullCalendar> tag available
  },
  data() {
    return {
      calendarOptions: {
        plugins: [ dayGridPlugin ],
        initialView: 'dayGridMonth',
 		



        events:[

        	{ title: 'event 1', start: '2020-09-22 00:00:00',

        	end:'2020-09-24 00:00:00', backgroundColor:"blue" },

			{ title: 'event 1', start: '2020-09-02',end: '2020-09-04T00:00:00.000Z', backgroundColor:"red" }, { title: 'event 1', date: '2020-08-05', backgroundColor:"green" },
			{ title: 'event 1', date: '2020-08-04', backgroundColor:"black" }
		]
      }
    }
  }
}
</script>