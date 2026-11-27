import{ad as P,b as T}from"./app-core.c3c5f825.js";import{_ as l}from"./vendor-other.49e2dbfb.js";import{n as C,an as _,ai as A,i as M,j as N,k,a0 as h,R as b,Q as R,T as I,M as W,ae as O,U as D,Y as j,P as G,ax as H}from"./vendor-vue-ui.8161fec3.js";import{_ as J}from"./Button.071881f9.js";import{B as Y}from"./Img.982e2a62.js";import{B as q}from"./Input.1293ca38.js";import{S as F}from"./Plus.93b3363f.js";import{_ as Q}from"./Trash.59df2bd4.js";import{_ as K}from"./_plugin-vue_export-helper.eefbdd86.js";const v="all-in-one-seo-pack",L=()=>typeof window.wp?.media=="function"?window.wp.media:typeof window.parent?.wp?.media=="function"?window.parent.wp.media:null,X=()=>{let e=null;const a=({title:i,buttonText:t,multiple:s=!1,type:r=null,onSelect:c})=>{e=L()({title:i,button:{text:t},multiple:s,library:r?{type:r}:{}}),e.on("select",()=>{const u=e.state().get("selection"),d=u.first();d&&c(s?u.toJSON():d.toJSON())}),e.on("close",()=>e.detach()),C(()=>{e.open()})},o=({multiple:i=!1,type:t=null,onSelect:s})=>{const u=(T().aioseo?.urls?.home||window.location.origin).replace(/\/$/,"")+"/wp-admin/",d=new URLSearchParams({breakdance_wpuiforbuilder_media:"1"});t&&d.set("types",t),i&&d.set("multiple","1");const n=document.createElement("div");if(n.className="aioseo-media-uploader-overlay",n.innerHTML=`
			<div class="aioseo-media-uploader-modal">
				<iframe src="${u}?${d.toString()}"></iframe>
			</div>
		`,!document.getElementById("aioseo-media-uploader-styles")){const m=document.createElement("style");m.id="aioseo-media-uploader-styles",m.textContent=`
				.aioseo-media-uploader-overlay {
					position: fixed;
					inset: 0;
					background: rgba(0, 0, 0, 0.7);
					z-index: 999999;
					display: flex;
					align-items: center;
					justify-content: center;
				}
				.aioseo-media-uploader-modal {
					background: #fff;
					width: 90%;
					height: 90%;
					max-width: 1200px;
					max-height: 800px;
					border-radius: 4px;
					overflow: hidden;
					display: flex;
					align-items: center;
					justify-content: center;
				}
				.aioseo-media-uploader-modal::before {
					content: '';
					width: 40px;
					height: 40px;
					border: 3px solid #e5e5e5;
					border-top-color: #005AE0;
					border-radius: 50%;
					animation: aioseoSpin 0.8s linear infinite;
					position: absolute;
				}
				.aioseo-media-uploader-modal iframe {
					width: 100%;
					height: 100%;
					border: none;
					background: #fff;
					opacity: 0;
					transition: opacity 0.15s ease-out;
				}
				.aioseo-media-uploader-modal.ready::before {
					display: none;
				}
				.aioseo-media-uploader-modal.ready iframe {
					opacity: 1;
				}
				@keyframes aioseoSpin {
					to { transform: rotate(360deg); }
				}
			`,document.head.appendChild(m)}document.body.appendChild(n);const g=n.querySelector("iframe"),B=n.querySelector(".aioseo-media-uploader-modal");g.addEventListener("load",()=>{setTimeout(()=>B.classList.add("ready"),300)});const y=()=>{document.removeEventListener("breakdanceMediaChooserSelect",x),document.removeEventListener("breakdanceMediaChooserClose",w),n.remove()},w=()=>C(y),x=m=>{const p=m.detail,E=i?Array.isArray(p)?p:[p]:Array.isArray(p)?p[0]:p;s(E),y()};n.addEventListener("click",m=>m.target===n&&y()),document.addEventListener("breakdanceMediaChooserSelect",x),document.addEventListener("breakdanceMediaChooserClose",w)};return{openMediaLibrary:({title:i=l("Choose Media",v),buttonText:t=l("Choose",v),multiple:s=!1,type:r=null,onSelect:c})=>{if(L()){a({title:i,buttonText:t,multiple:s,type:r,onSelect:c});return}if(P()){o({multiple:s,type:r,onSelect:c});return}window.alert(l("The media uploader is not available. Please paste the image URL directly.",v))}}},f="all-in-one-seo-pack",S={emits:["update:modelValue"],setup(){const{openMediaLibrary:e}=X();return{openMediaLibrary:e}},components:{BaseButton:J,BaseImg:Y,BaseInput:q,SvgCirclePlus:F,SvgTrash:Q},props:{baseSize:{type:String,default:"medium"},imgPreviewMaxHeight:{type:String,default:"525px"},imgPreviewMaxWidth:{type:String,default:"525px"},description:String,modelValue:{type:String,default:""},useDebounce:{type:Boolean,default:!0}},data(){return{strings:{description:l("Minimum size: 112px x 112px, The image must be in JPG, PNG, GIF, SVG, or WEBP format.",f),pasteYourImageUrl:l("Paste your image URL or select a new image",f),remove:l("Remove",f),uploadOrSelectImage:l("Upload or Select Image",f)}}},computed:{iconWidth(){return this.baseSize==="small"?"16":"20"}},methods:{setImgSrc(e){this.$emit("update:modelValue",e)},openUploadModal(){this.openMediaLibrary({title:l("Choose Image",f),buttonText:l("Choose Image",f),type:"image",onSelect:e=>this.setImgSrc(e?.url||null)})}}},V=()=>{H(e=>({v7c3f09dc:e.imgPreviewMaxHeight,v155b4a71:e.imgPreviewMaxWidth}))},U=S.setup;S.setup=U?(e,a)=>(V(),U(e,a)):V;const Z={class:"image-upload"},$={class:"aioseo-description"};function ee(e,a,o,z,i,t){const s=_("svg-trash"),r=_("base-button"),c=_("base-input"),u=_("svg-circle-plus"),d=_("base-img"),n=A("safe-html");return M(),N("div",{class:G(["aioseo-image-uploader",{"aioseo-image-uploader--has-image":!!o.modelValue}])},[k("div",Z,[h(c,{size:o.baseSize,modelValue:o.modelValue,placeholder:i.strings.pasteYourImageUrl,onChange:a[1]||(a[1]=g=>t.setImgSrc(g))},{"append-icon":b(()=>[o.modelValue?(M(),R(r,{key:0,size:o.baseSize,class:"remove-image",type:"gray",onClick:a[0]||(a[0]=I(g=>t.setImgSrc(null),["prevent"]))},{default:b(()=>[h(s,{width:t.iconWidth},null,8,["width"])]),_:1},8,["size"])):W("",!0)]),_:1},8,["size","modelValue","placeholder"]),h(r,{size:o.baseSize,class:"insert-image",type:"black",onClick:a[2]||(a[2]=I(g=>t.openUploadModal(),["prevent"]))},{default:b(()=>[h(u,{width:"14"}),O(" "+D(i.strings.uploadOrSelectImage),1)]),_:1},8,["size"])]),j(k("div",$,null,512),[[n,o.description||i.strings.description]]),h(d,{class:"image-preview",src:o.modelValue,debounce:o.useDebounce},null,8,["src","debounce"])],2)}const ce=K(S,[["render",ee],["__scopeId","data-v-012e4631"]]);export{ce as C};
