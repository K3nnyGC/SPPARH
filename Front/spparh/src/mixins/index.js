/*Mixins Functions*/
import axios from 'axios';
export default {
    methods: {
        goto: function(link){
            this.$router.push(link);
        },
        getDataUrl: function(data){
            let regex = new RegExp('[\\?&]'+data+'=([^&#]*)');
            let results = regex.exec(location.search);
            let id = results === null ? false : decodeURIComponent(results[1].replace(/\+/g, ' '));
            return parseInt(id);
        },
        change: function(b64Data, contentType='', sliceSize=512){
            const byteCharacters = atob(b64Data);
            const byteArrays = [];
            for (let offset = 0; offset < byteCharacters.length; offset += sliceSize) {
                const slice = byteCharacters.slice(offset, offset + sliceSize);
                const byteNumbers = new Array(slice.length);
                for (let i = 0; i < slice.length; i++) {
                    byteNumbers[i] = slice.charCodeAt(i);
                }
                const byteArray = new Uint8Array(byteNumbers);
                byteArrays.push(byteArray);
            }
            const blob = new Blob(byteArrays, {type: contentType});
            return blob;
        },
        //Ajaxs
        async request(api,method,extraHeaders,extraData,callback,fail,error,info={}){
            axios({
                method: method,
                url: api,
                headers: {
                    'Content-Type': 'application/json',
                    "Accept": "application/json, text/plain, */*",
                    ...extraHeaders
                },
                data:{
                    ...extraData
                }
            }).then(response => {
                if(response.data.ok){
                    callback(response.data,info);
                } else {
                    fail(response.data,info);
                }
            }).catch(err => {
                // console.log(error.message)
                error(err);
            }).finally( () => {         
            });
        }
    }
}