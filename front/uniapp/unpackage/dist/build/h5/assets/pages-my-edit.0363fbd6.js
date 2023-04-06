import{S as e,T as t,U as s,V as i,W as l,X as a,o,c as r,w as n,e as d,F as u,f as c,a as p,b as h,Y as f,d as m,h as y,m as g,j as b,i as F,Z as x,n as _,t as k,p as w,k as v,B as S,y as P,v as I,x as T,q as $,A as C,G as M,r as L}from"./index-d86fb874.js";import{L as D}from"./uni-cloud.es.f7ef21d5.js";import{_ as E,r as O}from"./uni-app.es.929e2abd.js";import{a as j,_ as V,b as U}from"./uni-forms.2336867c.js";const A="chooseAndUploadFile:fail";function B(e,t){return e.tempFiles.forEach(((e,s)=>{e.name||(e.name=e.path.substring(e.path.lastIndexOf("/")+1)),t&&(e.fileType=t),e.cloudPath=Date.now()+"_"+s+e.name.substring(e.name.lastIndexOf("."))})),e.tempFilePaths||(e.tempFilePaths=e.tempFiles.map((e=>e.path))),e}function z(e,t=5,s){const i=(e=JSON.parse(JSON.stringify(e))).length;let l=0,a=this;return new Promise((o=>{for(;l<t;)r();function r(){let t=l++;if(t>=i)return void(!e.find((e=>!e.url&&!e.errMsg))&&o(e));const n=e[t],d=a.files.findIndex((e=>e.uuid===n.uuid));n.url="",delete n.errMsg,D.uploadFile({filePath:n.path,cloudPath:n.cloudPath,fileType:n.fileType,onUploadProgress:e=>{e.index=d,s&&s(e)}}).then((e=>{n.url=e.fileID,n.index=d,t<i&&r()})).catch((e=>{n.errMsg=e.errMsg||e.message,n.index=d,t<i&&r()}))}}))}function N(e,{onChooseFile:t,onUploadProgress:s}){return e.then((e=>{if(t){const s=t(e);if(void 0!==s)return Promise.resolve(s).then((t=>void 0===t?e:t))}return e})).then((e=>!1===e?{errMsg:"chooseAndUploadFile:ok",tempFilePaths:[],tempFiles:[]}:e))}function R(i={type:"all"}){return"image"===i.type?N(function(t){const{count:s,sizeType:i=["original","compressed"],sourceType:l,extension:a}=t;return new Promise(((t,o)=>{e({count:s,sizeType:i,sourceType:l,extension:a,success(e){t(B(e,"image"))},fail(e){o({errMsg:e.errMsg.replace("chooseImage:fail",A)})}})}))}(i),i):"video"===i.type?N(function(e){const{camera:s,compressed:i,maxDuration:l,sourceType:a,extension:o}=e;return new Promise(((e,r)=>{t({camera:s,compressed:i,maxDuration:l,sourceType:a,extension:o,success(t){const{tempFilePath:s,duration:i,size:l,height:a,width:o}=t;e(B({errMsg:"chooseVideo:ok",tempFilePaths:[s],tempFiles:[{name:t.tempFile&&t.tempFile.name||"",path:s,size:l,type:t.tempFile&&t.tempFile.type||"",width:o,height:a,duration:i,fileType:"video",cloudPath:""}]},"video"))},fail(e){r({errMsg:e.errMsg.replace("chooseVideo:fail",A)})}})}))}(i),i):N(function(e){const{count:t,extension:i}=e;return new Promise(((e,l)=>{let a=s;if("undefined"!=typeof wx&&"function"==typeof wx.chooseMessageFile&&(a=wx.chooseMessageFile),"function"!=typeof a)return l({errMsg:A+" 请指定 type 类型，该平台仅支持选择 image 或 video。"});a({type:"all",count:t,extension:i,success(t){e(B(t))},fail(e){l({errMsg:e.errMsg.replace("chooseFile:fail",A)})}})}))}(i),i)}const q=e=>{const t=e.lastIndexOf("."),s=e.length;return{name:e.substring(0,t),ext:e.substring(t+1,s)}},J=e=>{if(Array.isArray(e))return e;return e.replace(/(\[|\])/g,"").split(",")},G=async(e,t="image")=>{const s=q(e.name).ext.toLowerCase();let i={name:e.name,uuid:e.uuid,extname:s||"",cloudPath:e.cloudPath,fileType:e.fileType,url:e.path||e.path,size:e.size,image:{},path:e.path,video:{}};if("image"===t){const t=await(a=e.path,new Promise(((e,t)=>{l({src:a,success(t){e(t)},fail(e){t(e)}})})));delete i.video,i.image.width=t.width,i.image.height=t.height,i.image.location=t.path}else delete i.image;var a;return i};const H=E({name:"uniFilePicker",components:{uploadImage:E({name:"uploadImage",emits:["uploadFiles","choose","delFile"],props:{filesList:{type:Array,default:()=>[]},disabled:{type:Boolean,default:!1},disablePreview:{type:Boolean,default:!1},limit:{type:[Number,String],default:9},imageStyles:{type:Object,default:()=>({width:"auto",height:"auto",border:{}})},delIcon:{type:Boolean,default:!0},readonly:{type:Boolean,default:!1}},computed:{styles(){return Object.assign({width:"auto",height:"auto",border:{}},this.imageStyles)},boxStyle(){const{width:e="auto",height:t="auto"}=this.styles;let s={};"auto"===t?"auto"!==e?(s.height=this.value2px(e),s["padding-top"]=0):s.height=0:(s.height=this.value2px(t),s["padding-top"]=0),s.width="auto"===e?"auto"!==t?this.value2px(t):"33.3%":this.value2px(e);let i="";for(let l in s)i+=`${l}:${s[l]};`;return i},borderStyle(){let{border:e}=this.styles,t={};if("boolean"==typeof e)t.border=e?"1px #eee solid":"none";else{let s=e&&e.width||1;s=this.value2px(s);let i=e&&e.radius||3;i=this.value2px(i),t={"border-width":s,"border-style":e&&e.style||"solid","border-color":e&&e.color||"#eee","border-radius":i}}let s="";for(let i in t)s+=`${i}:${t[i]};`;return s}},methods:{uploadFiles(e,t){this.$emit("uploadFiles",e)},choose(){this.$emit("choose")},delFile(e){this.$emit("delFile",e)},prviewImage(e,t){let s=[];1===Number(this.limit)&&this.disablePreview&&!this.disabled&&this.$emit("choose"),this.disablePreview||(this.filesList.forEach((e=>{s.push(e.url)})),a({urls:s,current:t}))},value2px:e=>("number"==typeof e?e+="px":-1===e.indexOf("%")&&(e=-1!==e.indexOf("px")?e:e+"px"),e)}},[["render",function(e,t,s,i,l,a){const _=b,k=F,w=x;return o(),r(k,{class:"uni-file-picker__container"},{default:n((()=>[(o(!0),d(u,null,c(s.filesList,((e,t)=>(o(),r(k,{class:"file-picker__box",key:t,style:p(a.boxStyle)},{default:n((()=>[h(k,{class:"file-picker__box-content",style:p(a.borderStyle)},{default:n((()=>[h(_,{class:"file-image",src:e.url,mode:"aspectFill",onClick:f((s=>a.prviewImage(e,t)),["stop"])},null,8,["src","onClick"]),s.delIcon&&!s.readonly?(o(),r(k,{key:0,class:"icon-del-box",onClick:f((e=>a.delFile(t)),["stop"])},{default:n((()=>[h(k,{class:"icon-del"}),h(k,{class:"icon-del rotate"})])),_:2},1032,["onClick"])):m("",!0),e.progress&&100!==e.progress||0===e.progress?(o(),r(k,{key:1,class:"file-picker__progress"},{default:n((()=>[h(w,{class:"file-picker__progress-item",percent:-1===e.progress?0:e.progress,"stroke-width":"4",backgroundColor:e.errMsg?"#ff5a5f":"#EBEBEB"},null,8,["percent","backgroundColor"])])),_:2},1024)):m("",!0),e.errMsg?(o(),r(k,{key:2,class:"file-picker__mask",onClick:f((s=>a.uploadFiles(e,t)),["stop"])},{default:n((()=>[y(" 点击重试 ")])),_:2},1032,["onClick"])):m("",!0)])),_:2},1032,["style"])])),_:2},1032,["style"])))),128)),s.filesList.length<s.limit&&!s.readonly?(o(),r(k,{key:0,class:"file-picker__box",style:p(a.boxStyle)},{default:n((()=>[h(k,{class:"file-picker__box-content is-add",style:p(a.borderStyle),onClick:a.choose},{default:n((()=>[g(e.$slots,"default",{},(()=>[h(k,{class:"icon-add"}),h(k,{class:"icon-add rotate"})]),!0)])),_:3},8,["style","onClick"])])),_:3},8,["style"])):m("",!0)])),_:3})}],["__scopeId","data-v-86b162f5"]]),uploadFile:E({name:"uploadFile",emits:["uploadFiles","choose","delFile"],props:{filesList:{type:Array,default:()=>[]},delIcon:{type:Boolean,default:!0},limit:{type:[Number,String],default:9},showType:{type:String,default:""},listStyles:{type:Object,default:()=>({border:!0,dividline:!0,borderStyle:{}})},readonly:{type:Boolean,default:!1}},computed:{list(){let e=[];return this.filesList.forEach((t=>{e.push(t)})),e},styles(){return Object.assign({border:!0,dividline:!0,"border-style":{}},this.listStyles)},borderStyle(){let{borderStyle:e,border:t}=this.styles,s={};if(t){let t=e&&e.width||1;t=this.value2px(t);let i=e&&e.radius||5;i=this.value2px(i),s={"border-width":t,"border-style":e&&e.style||"solid","border-color":e&&e.color||"#eee","border-radius":i}}else s.border="none";let i="";for(let l in s)i+=`${l}:${s[l]};`;return i},borderLineStyle(){let e={},{borderStyle:t}=this.styles;if(t&&t.color&&(e["border-color"]=t.color),t&&t.width){let s=t&&t.width||1,i=t&&t.style||0;"number"==typeof s?s+="px":s=s.indexOf("px")?s:s+"px",e["border-width"]=s,"number"==typeof i?i+="px":i=i.indexOf("px")?i:i+"px",e["border-top-style"]=i}let s="";for(let i in e)s+=`${i}:${e[i]};`;return s}},methods:{uploadFiles(e,t){this.$emit("uploadFiles",{item:e,index:t})},choose(){this.$emit("choose")},delFile(e){this.$emit("delFile",e)},value2px:e=>("number"==typeof e?e+="px":e=-1!==e.indexOf("px")?e:e+"px",e)}},[["render",function(e,t,s,i,l,a){const b=F,w=x;return o(),r(b,{class:"uni-file-picker__files"},{default:n((()=>[s.readonly?m("",!0):(o(),r(b,{key:0,class:"files-button",onClick:a.choose},{default:n((()=>[g(e.$slots,"default",{},void 0,!0)])),_:3},8,["onClick"])),a.list.length>0?(o(),r(b,{key:1,class:"uni-file-picker__lists is-text-box",style:p(a.borderStyle)},{default:n((()=>[(o(!0),d(u,null,c(a.list,((e,t)=>(o(),r(b,{class:_(["uni-file-picker__lists-box",{"files-border":0!==t&&a.styles.dividline}]),key:t,style:p(0!==t&&a.styles.dividline&&a.borderLineStyle)},{default:n((()=>[h(b,{class:"uni-file-picker__item"},{default:n((()=>[h(b,{class:"files__name"},{default:n((()=>[y(k(e.name),1)])),_:2},1024),s.delIcon&&!s.readonly?(o(),r(b,{key:0,class:"icon-del-box icon-files",onClick:e=>a.delFile(t)},{default:n((()=>[h(b,{class:"icon-del icon-files"}),h(b,{class:"icon-del rotate"})])),_:2},1032,["onClick"])):m("",!0)])),_:2},1024),e.progress&&100!==e.progress||0===e.progress?(o(),r(b,{key:0,class:"file-picker__progress"},{default:n((()=>[h(w,{class:"file-picker__progress-item",percent:-1===e.progress?0:e.progress,"stroke-width":"4",backgroundColor:e.errMsg?"#ff5a5f":"#EBEBEB"},null,8,["percent","backgroundColor"])])),_:2},1024)):m("",!0),"error"===e.status?(o(),r(b,{key:1,class:"file-picker__mask",onClick:f((s=>a.uploadFiles(e,t)),["stop"])},{default:n((()=>[y(" 点击重试 ")])),_:2},1032,["onClick"])):m("",!0)])),_:2},1032,["class","style"])))),128))])),_:1},8,["style"])):m("",!0)])),_:3})}],["__scopeId","data-v-5d376bd5"]])},options:{virtualHost:!0},emits:["select","success","fail","progress","delete","update:modelValue","input"],props:{modelValue:{type:[Array,Object],default:()=>[]},disabled:{type:Boolean,default:!1},disablePreview:{type:Boolean,default:!1},delIcon:{type:Boolean,default:!0},autoUpload:{type:Boolean,default:!0},limit:{type:[Number,String],default:9},mode:{type:String,default:"grid"},fileMediatype:{type:String,default:"image"},fileExtname:{type:[Array,String],default:()=>[]},title:{type:String,default:""},listStyles:{type:Object,default:()=>({border:!0,dividline:!0,borderStyle:{}})},imageStyles:{type:Object,default:()=>({width:"auto",height:"auto"})},readonly:{type:Boolean,default:!1},returnType:{type:String,default:"array"},sizeType:{type:Array,default:()=>["original","compressed"]},sourceType:{type:Array,default:()=>["album","camera"]}},data:()=>({files:[],localValue:[]}),watch:{modelValue:{handler(e,t){this.setValue(e,t)},immediate:!0}},computed:{filesList(){let e=[];return this.files.forEach((t=>{e.push(t)})),e},showType(){return"image"===this.fileMediatype?this.mode:"list"},limitLength(){return"object"===this.returnType?1:this.limit?this.limit>=9?9:this.limit:1}},created(){D.config&&D.config.provider||(this.noSpace=!0,D.chooseAndUploadFile=R),this.form=this.getForm("uniForms"),this.formItem=this.getForm("uniFormsItem"),this.form&&this.formItem&&this.formItem.name&&(this.rename=this.formItem.name,this.form.inputChildrens.push(this))},methods:{clearFiles(e){0===e||e?this.files.splice(e,1):(this.files=[],this.$nextTick((()=>{this.setEmit()}))),this.$nextTick((()=>{this.setEmit()}))},upload(){let e=[];return this.files.forEach(((t,s)=>{"ready"!==t.status&&"error"!==t.status||e.push(Object.assign({},t))})),this.uploadFiles(e)},async setValue(e,t){const s=async e=>{let t="";return t=e.fileID?e.fileID:e.url,/cloud:\/\/([\w.]+\/?)\S*/.test(t)&&(e.fileID=t,e.url=await this.getTempFileURL(t)),e.url&&(e.path=e.url),e};if("object"===this.returnType)e?await s(e):e={};else{e||(e=[]);for(let t=0;t<e.length;t++){let i=e[t];await s(i)}}this.localValue=e,this.form&&this.formItem&&!this.is_reset&&(this.is_reset=!1,this.formItem.setValue(this.localValue));let i=Object.keys(e).length>0?e:[];this.files=[].concat(i)},choose(){this.disabled||(this.files.length>=Number(this.limitLength)&&"grid"!==this.showType&&"array"===this.returnType?i({title:`您最多选择 ${this.limitLength} 个文件`,icon:"none"}):this.chooseFiles())},chooseFiles(){const e=J(this.fileExtname);D.chooseAndUploadFile({type:this.fileMediatype,compressed:!1,sizeType:this.sizeType,sourceType:this.sourceType,extension:e.length>0?e:void 0,count:this.limitLength-this.files.length,onChooseFile:this.chooseFileCallback,onUploadProgress:e=>{this.setProgress(e,e.index)}}).then((e=>{this.setSuccessAndError(e.tempFiles)})).catch((e=>{console.log("选择失败",e)}))},async chooseFileCallback(e){const t=J(this.fileExtname);(1===Number(this.limitLength)&&this.disablePreview&&!this.disabled||"object"===this.returnType)&&(this.files=[]);let{filePaths:s,files:l}=((e,t)=>{let s=[],l=[];return t&&0!==t.length?(e.tempFiles.forEach((e=>{const i=q(e.name).ext.toLowerCase();-1!==t.indexOf(i)&&(l.push(e),s.push(e.path))})),l.length!==e.tempFiles.length&&i({title:`当前选择了${e.tempFiles.length}个文件 ，${e.tempFiles.length-l.length} 个文件格式不正确`,icon:"none",duration:5e3}),{filePaths:s,files:l}):{filePaths:s,files:l}})(e,t);t&&t.length>0||(s=e.tempFilePaths,l=e.tempFiles);let a=[];for(let i=0;i<l.length&&!(this.limitLength-this.files.length<=0);i++){l[i].uuid=Date.now();let e=await G(l[i],this.fileMediatype);e.progress=0,e.status="ready",this.files.push(e),a.push({...e,file:l[i]})}this.$emit("select",{tempFiles:a,tempFilePaths:s}),e.tempFiles=l,this.autoUpload&&!this.noSpace||(e.tempFiles=[])},uploadFiles(e){return e=[].concat(e),z.call(this,e,5,(e=>{this.setProgress(e,e.index,!0)})).then((e=>(this.setSuccessAndError(e),e))).catch((e=>{console.log(e)}))},async setSuccessAndError(e,t){let s=[],i=[],l=[],a=[];for(let o=0;o<e.length;o++){const t=e[o],r=t.uuid?this.files.findIndex((e=>e.uuid===t.uuid)):t.index;if(-1===r||!this.files)break;if("request:fail"===t.errMsg)this.files[r].url=t.path,this.files[r].status="error",this.files[r].errMsg=t.errMsg,i.push(this.files[r]),a.push(this.files[r].url);else{this.files[r].errMsg="",this.files[r].fileID=t.url;/cloud:\/\/([\w.]+\/?)\S*/.test(t.url)?this.files[r].url=await this.getTempFileURL(t.url):this.files[r].url=t.url,this.files[r].status="success",this.files[r].progress+=1,s.push(this.files[r]),l.push(this.files[r].fileID)}}s.length>0&&(this.setEmit(),this.$emit("success",{tempFiles:this.backObject(s),tempFilePaths:l})),i.length>0&&this.$emit("fail",{tempFiles:this.backObject(i),tempFilePaths:a})},setProgress(e,t,s){this.files.length;const i=Math.round(100*e.loaded/e.total);let l=t;s||(l=this.files.findIndex((t=>t.uuid===e.tempFile.uuid))),-1!==l&&this.files[l]&&(this.files[l].progress=i-1,this.$emit("progress",{index:l,progress:parseInt(i),tempFile:this.files[l]}))},delFile(e){this.$emit("delete",{tempFile:this.files[e],tempFilePath:this.files[e].url}),this.files.splice(e,1),this.$nextTick((()=>{this.setEmit()}))},getFileExt(e){const t=e.lastIndexOf("."),s=e.length;return{name:e.substring(0,t),ext:e.substring(t+1,s)}},setEmit(){let e=[];"object"===this.returnType?(e=this.backObject(this.files)[0],this.localValue=e||null):(e=this.backObject(this.files),this.localValue||(this.localValue=[]),this.localValue=[...e]),this.$emit("update:modelValue",this.localValue)},backObject(e){let t=[];return e.forEach((e=>{t.push({extname:e.extname,fileType:e.fileType,image:e.image,name:e.name,path:e.path,size:e.size,fileID:e.fileID,url:e.url})})),t},async getTempFileURL(e){e={fileList:[].concat(e)};return(await D.getTempFileURL(e)).fileList[0].tempFileURL||""},getForm(e="uniForms"){let t=this.$parent,s=t.$options.name;for(;s!==e;){if(t=t.$parent,!t)return!1;s=t.$options.name}return t}}},[["render",function(e,t,s,i,l,a){const d=v,u=F,c=w("upload-image"),p=S,f=w("upload-file");return o(),r(u,{class:"uni-file-picker"},{default:n((()=>[s.title?(o(),r(u,{key:0,class:"uni-file-picker__header"},{default:n((()=>[h(d,{class:"file-title"},{default:n((()=>[y(k(s.title),1)])),_:1}),h(d,{class:"file-count"},{default:n((()=>[y(k(a.filesList.length)+"/"+k(a.limitLength),1)])),_:1})])),_:1})):m("",!0),"image"===s.fileMediatype&&"grid"===a.showType?(o(),r(c,{key:1,readonly:s.readonly,"image-styles":s.imageStyles,"files-list":a.filesList,limit:a.limitLength,disablePreview:s.disablePreview,delIcon:s.delIcon,onUploadFiles:a.uploadFiles,onChoose:a.choose,onDelFile:a.delFile},{default:n((()=>[g(e.$slots,"default",{},(()=>[h(u,{class:"is-add"},{default:n((()=>[h(u,{class:"icon-add"}),h(u,{class:"icon-add rotate"})])),_:1})]),!0)])),_:3},8,["readonly","image-styles","files-list","limit","disablePreview","delIcon","onUploadFiles","onChoose","onDelFile"])):m("",!0),"image"!==s.fileMediatype||"grid"!==a.showType?(o(),r(f,{key:2,readonly:s.readonly,"list-styles":s.listStyles,"files-list":a.filesList,showType:a.showType,delIcon:s.delIcon,onUploadFiles:a.uploadFiles,onChoose:a.choose,onDelFile:a.delFile},{default:n((()=>[g(e.$slots,"default",{},(()=>[h(p,{type:"primary",size:"mini"},{default:n((()=>[y("选择文件")])),_:1})]),!0)])),_:3},8,["readonly","list-styles","files-list","showType","delIcon","onUploadFiles","onChoose","onDelFile"])):m("",!0)])),_:3})}],["__scopeId","data-v-c59319f7"]]);const W=E({data:()=>({formData:{avatar:[],desc:""}}),onShow(){var e=this;P().globalData.checkLogin()?I.get(T.getApi("myProfile"),null,(function(t){if(0==t.code){var s=new Array;s.push({name:"avatar",extname:"png",url:t.data.avatar}),e.formData.avatar=s,e.formData.desc=t.data.desc}else $.error(t.message)}),null):C(-1)},methods:{select(e){var t=this;if(e.tempFiles[0].size/1048576>2)return $.error("the image size over 2M"),!1;var s=e.tempFilePaths[0];I.upload(T.getApi("upload"),s,(function(e){0==e.code?t.formData.avatar.push({name:e.data.filename,extname:e.data.ext,url:e.data.filepath}):$.error(e.message)}),{type:"image"})},progress(e){$.loading(e.progress)},success(e){$.success("success")},fail(e){$.error(e.errorMessage)},edit(){I.post(T.getApi("myEdit"),this.formData,(function(e){0==e.code?M({url:"/pages/my/index"}):$.error(e.message)}),{"Content-Type":"application/json"})},submit(){this.$refs.form.validate().then((e=>{this.edit()})).catch((e=>{console.log(e)}))}}},[["render",function(e,t,s,i,l,a){const d=O(L("uni-file-picker"),H),u=O(L("uni-forms-item"),j),c=O(L("uni-easyinput"),V),p=O(L("uni-forms"),U),f=S,m=F;return o(),r(m,{class:"p-30"},{default:n((()=>[h(p,{ref:"form",model:l.formData,rules:e.rules,"label-position":"top"},{default:n((()=>[h(u,{label:"Avatar",name:"avatar"},{default:n((()=>[h(d,{modelValue:l.formData.avatar,"onUpdate:modelValue":t[0]||(t[0]=e=>l.formData.avatar=e),fileMediatype:"image","file-extname":"jpg,png",limit:"1",mode:"grid",onSelect:a.select,onProgress:a.progress,onSuccess:a.success,onFail:a.fail},null,8,["modelValue","onSelect","onProgress","onSuccess","onFail"])])),_:1}),h(u,{label:"Description",name:"desc"},{default:n((()=>[h(c,{type:"textarea",modelValue:l.formData.desc,"onUpdate:modelValue":t[1]||(t[1]=e=>l.formData.desc=e),placeholder:""},null,8,["modelValue"])])),_:1})])),_:1},8,["model","rules"]),h(f,{type:"primary",onClick:a.submit},{default:n((()=>[y("Update")])),_:1},8,["onClick"])])),_:1})}],["__scopeId","data-v-de459ae5"]]);export{W as default};