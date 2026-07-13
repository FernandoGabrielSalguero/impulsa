import {W as W$1,m as q,bO as Er,aS as x,aB as CE,aC as cu,aM as ME,bi as Sr,bp as _I,aD as Dr,aF as Hp,aR as JD,n as nh,e as fh,bg as Ue,aO as dy,bb as ge,bk as Gm,b_ as xE,T,bs as F0,aJ as _,bw as ye,bj as Ne$1,i as k0,aa as gw,b$ as P0,c0 as Us,V as Vo,bD as ne$1,bu as tt$1,aN as Er$1,l as nd,b8 as Hl,a7 as be,c1 as jg,at as Yt$1,b0 as Bl,aZ as Rg,aK as B0,w as wE,c2 as jn$1,aG as hD,H as $p,a as yi$1,z as zE,aH as gD,b as Vc,q as qv,Q as QE,p as ch,bm as sh,r as ID,bn as yD,bo as vD,c3 as ah,bB as ih,c4 as Ts,c5 as d,c6 as $o,c7 as s,aT as V0,c8 as eh,ac as Qp,aE as mw,c9 as Cg,ca as pe$1,x as je,bt as j,b6 as Kp,b4 as Hc,b7 as Bc,f as fD,Y as Yp,Z as Zp,a6 as ED,cb as gr$1,bE as L$1,bT as Et,aW as S,cc as Pn$1,bQ as jo,cd as b,ce as Lc,aQ as Ce,bl as vo,$ as $D,d as Ih,bU as _$1,cf as Vg,cg as uw}from'./main-IHQSQGJR.js';var ei=(()=>{class i{_renderer;_elementRef;onChange=e=>{};onTouched=()=>{};constructor(e,n){this._renderer=e,this._elementRef=n;}setProperty(e,n){this._renderer.setProperty(this._elementRef.nativeElement,e,n);}registerOnTouched(e){this.onTouched=e;}registerOnChange(e){this.onChange=e;}setDisabledState(e){this.setProperty("disabled",e);}static \u0275fac=function(n){return new(n||i)(Sr(_I),Sr(Dr))};static \u0275dir=ME({type:i})}return i})(),ti=(()=>{class i extends ei{static \u0275fac=(()=>{let e;return function(r){return (e||(e=dy(i)))(r||i)}})();static \u0275dir=ME({type:i,features:[Hp]})}return i})(),Pe=new x("");var tn={provide:Pe,useExisting:vo(()=>ii),multi:true};function nn(){let i=pe$1()?pe$1().getUserAgent():"";return /android (\d+)/.test(i.toLowerCase())}var rn=new x(""),ii=(()=>{class i extends ei{_compositionMode;_composing=false;constructor(e,n,r){super(e,n),this._compositionMode=r,this._compositionMode==null&&(this._compositionMode=!nn());}writeValue(e){let n=e??"";this.setProperty("value",n);}_handleInput(e){(!this._compositionMode||this._compositionMode&&!this._composing)&&this.onChange(e);}_compositionStart(){this._composing=true;}_compositionEnd(e){this._composing=false,this._compositionMode&&this.onChange(e);}static \u0275fac=function(n){return new(n||i)(Sr(_I),Sr(Dr),Sr(rn,8))};static \u0275dir=ME({type:i,selectors:[["input","formControlName","",3,"type","checkbox",3,"ngNoCva",""],["textarea","formControlName","",3,"ngNoCva",""],["input","formControl","",3,"type","checkbox",3,"ngNoCva",""],["textarea","formControl","",3,"ngNoCva",""],["input","ngModel","",3,"type","checkbox",3,"ngNoCva",""],["textarea","ngModel","",3,"ngNoCva",""],["","ngDefaultControl",""]],hostBindings:function(n,r){n&1&&nh("input",function(a){return r._handleInput(a.target.value)})("blur",function(){return r.onTouched()})("compositionstart",function(){return r._compositionStart()})("compositionend",function(a){return r._compositionEnd(a.target.value)});},standalone:false,features:[JD([tn]),Hp]})}return i})();function it(i){return i==null||nt(i)===0}function nt(i){return i==null?null:Array.isArray(i)||typeof i=="string"?i.length:i instanceof Set?i.size:null}var L=new x(""),re=new x(""),on=/^(?=.{1,254}$)(?=.{1,64}@)[a-zA-Z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&'*+/=?^_`{|}~-]+)*@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/,pe=class{static min(t){return ni(t)}static max(t){return ri(t)}static required(t){return oi(t)}static requiredTrue(t){return an(t)}static email(t){return sn(t)}static minLength(t){return ln(t)}static maxLength(t){return ai(t)}static pattern(t){return dn(t)}static nullValidator(t){return Ae()}static compose(t){return fi(t)}static composeAsync(t){return mi(t)}};function ni(i){return t=>{if(t.value==null||i==null)return null;let e=parseFloat(t.value);return !isNaN(e)&&e<i?{min:{min:i,actual:t.value}}:null}}function ri(i){return t=>{if(t.value==null||i==null)return null;let e=parseFloat(t.value);return !isNaN(e)&&e>i?{max:{max:i,actual:t.value}}:null}}function oi(i){return it(i.value)?{required:true}:null}function an(i){return i.value===true?null:{required:true}}function sn(i){return it(i.value)||on.test(i.value)?null:{email:true}}function ln(i){return t=>{let e=t.value?.length??nt(t.value);return e===null||e===0?null:e<i?{minlength:{requiredLength:i,actualLength:e}}:null}}function ai(i){return t=>{let e=t.value?.length??nt(t.value);return e!==null&&e>i?{maxlength:{requiredLength:i,actualLength:e}}:null}}function dn(i){if(!i)return Ae;let t,e;return typeof i=="string"?(e="",i.charAt(0)!=="^"&&(e+="^"),e+=i,i.charAt(i.length-1)!=="$"&&(e+="$"),t=new RegExp(e)):(e=i.toString(),t=i),n=>{if(it(n.value))return null;let r=n.value;return t.test(r)?null:{pattern:{requiredPattern:e,actualValue:r}}}}function Ae(i){return null}function si(i){return i!=null}function li(i){return Lc(i)?Ce(i):i}function di(i){let t={};return i.forEach(e=>{t=e!=null?q(q({},t),e):t;}),Object.keys(t).length===0?null:t}function ci(i,t){return t.map(e=>e(i))}function cn(i){return !i.validate}function ui(i){return i.map(t=>cn(t)?t:e=>t.validate(e))}function fi(i){if(!i)return null;let t=i.filter(si);return t.length==0?null:function(e){return di(ci(e,t))}}function rt(i){return i!=null?fi(ui(i)):null}function mi(i){if(!i)return null;let t=i.filter(si);return t.length==0?null:function(e){let n=ci(e,t).map(li);return Cg(n).pipe(be(di))}}function ot(i){return i!=null?mi(ui(i)):null}function Zt(i,t){return i===null?[t]:Array.isArray(i)?[...i,t]:[i,t]}function hi(i){return i._rawValidators}function pi(i){return i._rawAsyncValidators}function Ke(i){return i?Array.isArray(i)?i:[i]:[]}function Ee(i,t){return Array.isArray(i)?i.includes(t):i===t}function Qt(i,t){let e=Ke(t);return Ke(i).forEach(r=>{Ee(e,r)||e.push(r);}),e}function Yt(i,t){return Ke(t).filter(e=>!Ee(i,e))}var Se=class{get value(){return this.control?this.control.value:null}get valid(){return this.control?this.control.valid:null}get invalid(){return this.control?this.control.invalid:null}get pending(){return this.control?this.control.pending:null}get disabled(){return this.control?this.control.disabled:null}get enabled(){return this.control?this.control.enabled:null}get errors(){return this.control?this.control.errors:null}get pristine(){return this.control?this.control.pristine:null}get dirty(){return this.control?this.control.dirty:null}get touched(){return this.control?this.control.touched:null}get status(){return this.control?this.control.status:null}get untouched(){return this.control?this.control.untouched:null}get statusChanges(){return this.control?this.control.statusChanges:null}get valueChanges(){return this.control?this.control.valueChanges:null}get path(){return null}_composedValidatorFn;_composedAsyncValidatorFn;_rawValidators=[];_rawAsyncValidators=[];_setValidators(t){this._rawValidators=t||[],this._composedValidatorFn=rt(this._rawValidators);}_setAsyncValidators(t){this._rawAsyncValidators=t||[],this._composedAsyncValidatorFn=ot(this._rawAsyncValidators);}get validator(){return this._composedValidatorFn||null}get asyncValidator(){return this._composedAsyncValidatorFn||null}_onDestroyCallbacks=[];_registerOnDestroy(t){this._onDestroyCallbacks.push(t);}_invokeOnDestroyCallbacks(){this._onDestroyCallbacks.forEach(t=>t()),this._onDestroyCallbacks=[];}reset(t=void 0){this.control?.reset(t);}hasError(t,e){return this.control?this.control.hasError(t,e):false}getError(t,e){return this.control?this.control.getError(t,e):null}},C=class extends Se{name;get formDirective(){return null}get path(){return null}};var ce="VALID",we="INVALID",ee="PENDING",ue="DISABLED",U=class{},Ie=class extends U{value;source;constructor(t,e){super(),this.value=t,this.source=e;}},me=class extends U{pristine;source;constructor(t,e){super(),this.pristine=t,this.source=e;}},he=class extends U{touched;source;constructor(t,e){super(),this.touched=t,this.source=e;}},te=class extends U{status;source;constructor(t,e){super(),this.status=t,this.source=e;}},Re=class extends U{source;constructor(t){super(),this.source=t;}},W=class extends U{source;constructor(t){super(),this.source=t;}};function at(i){return (Le(i)?i.validators:i)||null}function un(i){return Array.isArray(i)?rt(i):i||null}function st(i,t){return (Le(t)?t.asyncValidators:i)||null}function fn(i){return Array.isArray(i)?ot(i):i||null}function Le(i){return i!=null&&!Array.isArray(i)&&typeof i=="object"}function gi(i,t,e){let n=i.controls;if(!(t?Object.keys(n):n).length)throw new b(1e3,"");if(!vi(n,e))throw new b(1001,"")}function _i(i,t,e){i._forEachChild((n,r)=>{if(e[r]===void 0)throw new b(-1002,"")});}var ie=class{_pendingDirty=false;_hasOwnPendingAsyncValidator=null;_pendingTouched=false;_onCollectionChange=()=>{};_updateOn;_hasRequired=Vo(false);_parent=null;_asyncValidationSubscription;_composedValidatorFn;_composedAsyncValidatorFn;_rawValidators;_rawAsyncValidators;value;constructor(t,e){this._assignValidators(t),this._assignAsyncValidators(e);}get validator(){return this._composedValidatorFn}set validator(t){this._rawValidators=this._composedValidatorFn=t,this._updateHasRequiredValidator();}get asyncValidator(){return this._composedAsyncValidatorFn}set asyncValidator(t){this._rawAsyncValidators=this._composedAsyncValidatorFn=t;}get parent(){return this._parent}get status(){return mw(this.statusReactive)}set status(t){mw(()=>this.statusReactive.set(t));}_status=gw(()=>this.statusReactive());statusReactive=Vo(void 0);get valid(){return this.status===ce}get invalid(){return this.status===we}get pending(){return this.status===ee}get disabled(){return this.status===ue}get enabled(){return this.status!==ue}errors;get pristine(){return mw(this.pristineReactive)}set pristine(t){mw(()=>this.pristineReactive.set(t));}_pristine=gw(()=>this.pristineReactive());pristineReactive=Vo(true);get dirty(){return !this.pristine}get touched(){return mw(this.touchedReactive)}set touched(t){mw(()=>this.touchedReactive.set(t));}_touched=gw(()=>this.touchedReactive());touchedReactive=Vo(false);get untouched(){return !this.touched}_events=new ne$1;events=this._events.asObservable();valueChanges;statusChanges;get updateOn(){return this._updateOn?this._updateOn:this.parent?this.parent.updateOn:"change"}setValidators(t){this._assignValidators(t);}setAsyncValidators(t){this._assignAsyncValidators(t);}addValidators(t){this.setValidators(Qt(t,this._rawValidators));}addAsyncValidators(t){this.setAsyncValidators(Qt(t,this._rawAsyncValidators));}removeValidators(t){this.setValidators(Yt(t,this._rawValidators));}removeAsyncValidators(t){this.setAsyncValidators(Yt(t,this._rawAsyncValidators));}hasValidator(t){return Ee(this._rawValidators,t)}hasAsyncValidator(t){return Ee(this._rawAsyncValidators,t)}clearValidators(){this.validator=null;}clearAsyncValidators(){this.asyncValidator=null;}markAsTouched(t={}){let e=this.touched===false;this.touched=true;let n=t.sourceControl??this;t.onlySelf||this._parent?.markAsTouched(W$1(q({},t),{sourceControl:n})),e&&t.emitEvent!==false&&this._events.next(new he(true,n));}markAllAsDirty(t={}){this.markAsDirty({onlySelf:true,emitEvent:t.emitEvent,sourceControl:this}),this._forEachChild(e=>e.markAllAsDirty(t));}markAllAsTouched(t={}){this.markAsTouched({onlySelf:true,emitEvent:t.emitEvent,sourceControl:this}),this._forEachChild(e=>e.markAllAsTouched(t));}markAsUntouched(t={}){let e=this.touched===true;this.touched=false,this._pendingTouched=false;let n=t.sourceControl??this;this._forEachChild(r=>{r.markAsUntouched({onlySelf:true,emitEvent:t.emitEvent,sourceControl:n});}),t.onlySelf||this._parent?._updateTouched(t,n),e&&t.emitEvent!==false&&this._events.next(new he(false,n));}markAsDirty(t={}){let e=this.pristine===true;this.pristine=false;let n=t.sourceControl??this;t.onlySelf||this._parent?.markAsDirty(W$1(q({},t),{sourceControl:n})),e&&t.emitEvent!==false&&this._events.next(new me(false,n));}markAsPristine(t={}){let e=this.pristine===false;this.pristine=true,this._pendingDirty=false;let n=t.sourceControl??this;this._forEachChild(r=>{r.markAsPristine({onlySelf:true,emitEvent:t.emitEvent});}),t.onlySelf||this._parent?._updatePristine(t,n),e&&t.emitEvent!==false&&this._events.next(new me(true,n));}markAsPending(t={}){this.status=ee;let e=t.sourceControl??this;t.emitEvent!==false&&(this._events.next(new te(this.status,e)),this.statusChanges.emit(this.status)),t.onlySelf||this._parent?.markAsPending(W$1(q({},t),{sourceControl:e}));}disable(t={}){let e=this._parentMarkedDirty(t.onlySelf);this.status=ue,this.errors=null,this._forEachChild(r=>{r.disable(W$1(q({},t),{onlySelf:true}));}),this._updateValue();let n=t.sourceControl??this;t.emitEvent!==false&&(this._events.next(new Ie(this.value,n)),this._events.next(new te(this.status,n)),this.valueChanges.emit(this.value),this.statusChanges.emit(this.status)),this._updateAncestors(W$1(q({},t),{skipPristineCheck:e}),this),this._onDisabledChange.forEach(r=>r(true));}enable(t={}){let e=this._parentMarkedDirty(t.onlySelf);this.status=ce,this._forEachChild(n=>{n.enable(W$1(q({},t),{onlySelf:true}));}),this.updateValueAndValidity({onlySelf:true,emitEvent:t.emitEvent}),this._updateAncestors(W$1(q({},t),{skipPristineCheck:e}),this),this._onDisabledChange.forEach(n=>n(false));}_updateAncestors(t,e){t.onlySelf||(this._parent?.updateValueAndValidity(t),t.skipPristineCheck||this._parent?._updatePristine({},e),this._parent?._updateTouched({},e));}setParent(t){this._parent=t;}getRawValue(){return this.value}updateValueAndValidity(t={}){if(this._setInitialStatus(),this._updateValue(),this.enabled){let n=this._cancelExistingSubscription();this.errors=this._runValidator(),this.status=this._calculateStatus(),(this.status===ce||this.status===ee)&&this._runAsyncValidator(n,t.emitEvent);}let e=t.sourceControl??this;t.emitEvent!==false&&(this._events.next(new Ie(this.value,e)),this._events.next(new te(this.status,e)),this.valueChanges.emit(this.value),this.statusChanges.emit(this.status)),t.onlySelf||this._parent?.updateValueAndValidity(W$1(q({},t),{sourceControl:e}));}_updateTreeValidity(t={emitEvent:true}){this._forEachChild(e=>e._updateTreeValidity(t)),this.updateValueAndValidity({onlySelf:true,emitEvent:t.emitEvent});}_setInitialStatus(){this.status=this._allControlsDisabled()?ue:ce;}_runValidator(){return this.validator?this.validator(this):null}_runAsyncValidator(t,e){if(this.asyncValidator){this.status=ee,this._hasOwnPendingAsyncValidator={emitEvent:e!==false,shouldHaveEmitted:t!==false};let n=li(this.asyncValidator(this));this._asyncValidationSubscription=n.subscribe(r=>{this._hasOwnPendingAsyncValidator=null,this.setErrors(r,{emitEvent:e,shouldHaveEmitted:t});});}}_cancelExistingSubscription(){if(this._asyncValidationSubscription){this._asyncValidationSubscription.unsubscribe();let t=(this._hasOwnPendingAsyncValidator?.emitEvent||this._hasOwnPendingAsyncValidator?.shouldHaveEmitted)??false;return this._hasOwnPendingAsyncValidator=null,t}return  false}setErrors(t,e={}){this.errors=t,this._updateControlsErrors(e.emitEvent!==false,this,e.shouldHaveEmitted);}get(t){let e=t;return e==null||(Array.isArray(e)||(e=e.split(".")),e.length===0)?null:e.reduce((n,r)=>n&&n._find(r),this)}getError(t,e){let n=e?this.get(e):this;return n?.errors?n.errors[t]:null}hasError(t,e){return !!this.getError(t,e)}get root(){let t=this;for(;t._parent;)t=t._parent;return t}_updateControlsErrors(t,e,n){this.status=this._calculateStatus(),t&&this.statusChanges.emit(this.status),(t||n)&&this._events.next(new te(this.status,e)),this._parent&&this._parent._updateControlsErrors(t,e,n);}_initObservables(){this.valueChanges=new Ue,this.statusChanges=new Ue;}_calculateStatus(){return this._allControlsDisabled()?ue:this.errors?we:this._hasOwnPendingAsyncValidator||this._anyControlsHaveStatus(ee)?ee:this._anyControlsHaveStatus(we)?we:ce}_anyControlsHaveStatus(t){return this._anyControls(e=>e.status===t)}_anyControlsDirty(){return this._anyControls(t=>t.dirty)}_anyControlsTouched(){return this._anyControls(t=>t.touched)}_updatePristine(t,e){let n=!this._anyControlsDirty(),r=this.pristine!==n;this.pristine=n,t.onlySelf||this._parent?._updatePristine(t,e),r&&this._events.next(new me(this.pristine,e));}_updateTouched(t={},e){this.touched=this._anyControlsTouched(),this._events.next(new he(this.touched,e)),t.onlySelf||this._parent?._updateTouched(t,e);}_onDisabledChange=[];_registerOnCollectionChange(t){this._onCollectionChange=t;}_setUpdateStrategy(t){Le(t)&&t.updateOn!=null&&(this._updateOn=t.updateOn);}_parentMarkedDirty(t){return !t&&!!this._parent?.dirty&&!this._parent._anyControlsDirty()}_find(t){return null}_assignValidators(t){this._rawValidators=Array.isArray(t)?t.slice():t,this._composedValidatorFn=un(this._rawValidators),this._updateHasRequiredValidator();}_assignAsyncValidators(t){this._rawAsyncValidators=Array.isArray(t)?t.slice():t,this._composedAsyncValidatorFn=fn(this._rawAsyncValidators);}_updateHasRequiredValidator(){mw(()=>this._hasRequired.set(this.hasValidator(pe.required)));}};function vi(i,t){return Object.hasOwn(i,t)}function mn(i){return i.tagName==="INPUT"||i.tagName==="SELECT"||i.tagName==="TEXTAREA"}function hn(i,t,e,n){switch(e){case "name":i.setAttribute(t,e,n);break;case "disabled":case "readonly":case "required":n?i.setAttribute(t,e,""):i.removeAttribute(t,e);break;case "max":case "min":case "minLength":case "maxLength":n!==void 0?i.setAttribute(t,e,n.toString()):i.removeAttribute(t,e);break}}var Je=class{kind;context;control;message;constructor({kind:t,context:e,control:n}){this.kind=t,this.context=e,this.control=n;}};function pn(i){return typeof i=="number"?i:parseInt(i,10)}function bi(i){return typeof i=="number"?i:parseFloat(i)}var Be=(()=>{class i{_validator=Ae;_onChange;_enabled;ngOnChanges(e){if(this.inputName in e){let n=this.normalizeInput(e[this.inputName].currentValue);this._enabled=this.enabled(n),this._validator=this._enabled?this.createValidator(n):Ae,this._onChange?.();}}validate(e){return this._validator(e)}registerOnValidatorChange(e){this._onChange=e;}enabled(e){return e!=null}static \u0275fac=function(n){return new(n||i)};static \u0275dir=ME({type:i,features:[Gm]})}return i})(),gn={provide:L,useExisting:vo(()=>_n),multi:true},_n=(()=>{class i extends Be{max;inputName="max";normalizeInput=e=>bi(e);createValidator=e=>ri(e);static \u0275fac=(()=>{let e;return function(r){return (e||(e=dy(i)))(r||i)}})();static \u0275dir=ME({type:i,selectors:[["input","type","number","max","","formControlName",""],["input","type","number","max","","formControl",""],["input","type","number","max","","ngModel",""]],hostVars:1,hostBindings:function(n,r){n&2&&Qp("max",r._enabled?r.max:null);},inputs:{max:"max"},standalone:false,features:[JD([gn]),Hp]})}return i})(),vn={provide:L,useExisting:vo(()=>bn),multi:true},bn=(()=>{class i extends Be{min;inputName="min";normalizeInput=e=>bi(e);createValidator=e=>ni(e);static \u0275fac=(()=>{let e;return function(r){return (e||(e=dy(i)))(r||i)}})();static \u0275dir=ME({type:i,selectors:[["input","type","number","min","","formControlName",""],["input","type","number","min","","formControl",""],["input","type","number","min","","ngModel",""]],hostVars:1,hostBindings:function(n,r){n&2&&Qp("min",r._enabled?r.min:null);},inputs:{min:"min"},standalone:false,features:[JD([vn]),Hp]})}return i})(),yn={provide:L,useExisting:vo(()=>yi),multi:true};var yi=(()=>{class i extends Be{required;inputName="required";normalizeInput=V0;createValidator=e=>oi;enabled(e){return e}static \u0275fac=(()=>{let e;return function(r){return (e||(e=dy(i)))(r||i)}})();static \u0275dir=ME({type:i,selectors:[["","required","","formControlName","",3,"type","checkbox"],["","required","","formControl","",3,"type","checkbox"],["","required","","ngModel","",3,"type","checkbox"]],hostVars:1,hostBindings:function(n,r){n&2&&Qp("required",r._enabled?"":null);},inputs:{required:"required"},standalone:false,features:[JD([yn]),Hp]})}return i})();var xn={provide:L,useExisting:vo(()=>Cn),multi:true},Cn=(()=>{class i extends Be{maxlength;inputName="maxlength";normalizeInput=e=>pn(e);createValidator=e=>ai(e);static \u0275fac=(()=>{let e;return function(r){return (e||(e=dy(i)))(r||i)}})();static \u0275dir=ME({type:i,selectors:[["","maxlength","","formControlName",""],["","maxlength","","formControl",""],["","maxlength","","ngModel",""]],hostVars:1,hostBindings:function(n,r){n&2&&Qp("maxlength",r._enabled?r.maxlength:null);},inputs:{maxlength:"maxlength"},standalone:false,features:[JD([xn]),Hp]})}return i})();var Dn=new x(""),_e=new x("",{factory:()=>ze}),ze="always";function lt(i,t){return [...t.path,i]}function xi(i,t,e=ze){dt(i,t),t.valueAccessor.writeValue(i.value),(i.disabled||e==="always")&&t.valueAccessor.setDisabledState?.(i.disabled),Mn(i,t),Vn(i,t),wn(i,t),Fn(i,t);}function Oe(i,t,e=true){let n=()=>{};t?.valueAccessor?.registerOnChange(n),t?.valueAccessor?.registerOnTouched(n),Te(i,t),i&&(t._invokeOnDestroyCallbacks(),i._registerOnCollectionChange(()=>{}));}function Ne(i,t){i.forEach(e=>{e.registerOnValidatorChange&&e.registerOnValidatorChange(t);});}function Fn(i,t){if(t.valueAccessor.setDisabledState){let e=n=>{t.valueAccessor.setDisabledState(n);};i.registerOnDisabledChange(e),t._registerOnDestroy(()=>{i._unregisterOnDisabledChange(e);});}}function dt(i,t){let e=hi(i);t.validator!==null?i.setValidators(Zt(e,t.validator)):typeof e=="function"&&i.setValidators([e]);let n=pi(i);t.asyncValidator!==null?i.setAsyncValidators(Zt(n,t.asyncValidator)):typeof n=="function"&&i.setAsyncValidators([n]);let r=()=>i.updateValueAndValidity();Ne(t._rawValidators,r),Ne(t._rawAsyncValidators,r);}function Te(i,t){let e=false;if(i!==null){if(t.validator!==null){let r=hi(i);if(Array.isArray(r)&&r.length>0){let o=r.filter(a=>a!==t.validator);o.length!==r.length&&(e=true,i.setValidators(o));}}if(t.asyncValidator!==null){let r=pi(i);if(Array.isArray(r)&&r.length>0){let o=r.filter(a=>a!==t.asyncValidator);o.length!==r.length&&(e=true,i.setAsyncValidators(o));}}}let n=()=>{};return Ne(t._rawValidators,n),Ne(t._rawAsyncValidators,n),e}function Mn(i,t){t.valueAccessor.registerOnChange(e=>{i._pendingValue=e,i._pendingChange=true,i._pendingDirty=true,i.updateOn==="change"&&Ci(i,t);});}function wn(i,t){t.valueAccessor.registerOnTouched(()=>{i._pendingTouched=true,i.updateOn==="blur"&&i._pendingChange&&Ci(i,t),i.updateOn!=="submit"&&i.markAsTouched();});}function Ci(i,t){i._pendingDirty&&i.markAsDirty(),i.setValue(i._pendingValue,{emitModelToViewChange:false}),t.viewToModelUpdate(i._pendingValue),i._pendingChange=false;}function Vn(i,t){let e=(n,r)=>{t.valueAccessor.writeValue(n),r&&t.viewToModelUpdate(n);};i.registerOnChange(e),t._registerOnDestroy(()=>{i._unregisterOnChange(e);});}function Di(i,t){dt(i,t);}function An(i,t){return Te(i,t)}function Fi(i,t){if(!i.hasOwnProperty("model"))return  false;let e=i.model;return e.isFirstChange()?true:!Object.is(t,e.currentValue)}function En(i){return Object.getPrototypeOf(i.constructor)===ti}function Mi(i,t){i._syncPendingControls(),t.forEach(e=>{let n=e.control;n.updateOn==="submit"&&n._pendingChange&&(e.viewToModelUpdate(n._pendingValue),n._pendingChange=false);});}function Sn(i,t){if(!t)return null;let e,n,r;return t.forEach(o=>{o.constructor===ii?e=o:En(o)?n=o:r=o;}),r||n||e||null}function In(i,t){let e=i.indexOf(t);e>-1&&i.splice(e,1);}var wi={provide:Dn,useFactory:()=>{let i=T(P,{self:true});return {setParseErrors:t=>{i.setParseErrorSource(t);},set onReset(t){i.onReset=t;}}}},P=class extends Se{_parent=null;name=null;valueAccessor=null;isCustomControlBased=false;userOnReset;resetSubscription;set onReset(t){this.userOnReset=t,this.resetSubscription?.unsubscribe(),this.resetSubscription=void 0,this.control&&(this.resetSubscription=this.control.events.subscribe(e=>{e instanceof W&&this.control&&this.userOnReset?.(this.control.value);}),this.subscription?.add(this.resetSubscription));}isNativeFormElement=false;rawValueAccessors;_selectedValueAccessor=null;get selectedValueAccessor(){return this._selectedValueAccessor??=Sn(this,this.rawValueAccessors)}parseErrorsValidator=null;renderer;injector;requiredValidatorViaDi;subscription;customControlBindings=null;constructor(t,e,n){super(),this.injector=t,this.renderer=e,this.rawValueAccessors=n,this.injector?.get(je)?.onDestroy(()=>{this.removeParseErrorsValidator(this.control),this.subscription?.unsubscribe();});}setupCustomControl(){this.subscription?.unsubscribe();let t=this.injector?.get(F0);if(!this.control||!t)return;let e=t.markForCheck.bind(t);this.subscription=new j,this.subscription.add(this.control.valueChanges.subscribe(e)),this.subscription.add(this.control.statusChanges.subscribe(e)),this.resetSubscription?.unsubscribe(),this.resetSubscription=void 0,this.userOnReset&&(this.resetSubscription=this.control.events.subscribe(n=>{n instanceof W&&this.control&&this.userOnReset?.(this.control.value);}),this.subscription.add(this.resetSubscription)),this.parseErrorsValidator&&this.control.addValidators(this.parseErrorsValidator);}ngControlCreate(t){!t.nativeElement.hasAttribute?.("ngNoCva")&&(this.rawValueAccessors&&this.rawValueAccessors.length>0||this.valueAccessor!==null)||!t.customControl||(this.isCustomControlBased=true,t.listenToCustomControlModel(r=>{this.control?.setValue(r,{emitModelToViewChange:false}),this.control?.markAsDirty(),this.viewToModelUpdate(r);}),t.listenToCustomControlOutput("touch",()=>{this.control?.markAsTouched();}),this.customControlBindings={},this.isNativeFormElement=mn(t.nativeElement),this.requiredValidatorViaDi=this._rawValidators.find(r=>r instanceof yi));}ngControlUpdate(t,e){if(!this.isCustomControlBased)return;let n=this.control,r=this.customControlBindings;Object.is(r.value,n.value)||(r.value=n.value,t.setCustomControlModelInput(n.value)),this.bindControlProperty(t,r,"touched",n.touched),this.bindControlProperty(t,r,"dirty",n.dirty),this.bindControlProperty(t,r,"valid",n.valid),this.bindControlProperty(t,r,"invalid",n.invalid),this.bindControlProperty(t,r,"pending",n.pending),this.bindControlProperty(t,r,"disabled",n.disabled),this.shouldBindRequired&&this.bindControlProperty(t,r,"required",this.isRequired);let o=n.errors;if(r.errors!==o){r.errors=o;let a=this._convertErrors(o);t.setInputOnDirectives("errors",a);}}get isRequired(){return (this.requiredValidatorViaDi?._enabled||this.control?._hasRequired())??false}get shouldBindRequired(){return  true}bindControlProperty(t,e,n,r){if(e[n]===r)return;e[n]=r;let o=t.setInputOnDirectives(n,r);this.isNativeFormElement&&!o&&(n==="disabled"||n==="required")&&this.renderer&&hn(this.renderer,t.nativeElement,n,r);}_convertErrors(t){if(t===null)return [];let e=this.control;return Object.entries(t).map(([n,r])=>new Je({context:r,kind:n,control:e}))}setParseErrorSource(t){if(t===void 0)return;let e=null,n=gw(()=>{let r=t();return r.length===0?null:r.reduce((o,a)=>(o[a.kind]=a,o),{})});this.parseErrorsValidator=(()=>e).bind(this),nd(()=>{e=n(),this.control?.updateValueAndValidity({emitEvent:false});},{injector:this.injector});}removeParseErrorsValidator(t){this.parseErrorsValidator&&(t?.removeValidators(this.parseErrorsValidator),t?.updateValueAndValidity({emitEvent:false}));}},ke=class{_cd;constructor(t){this._cd=t;}get isTouched(){return this._cd?.control?._touched?.(),!!this._cd?.control?.touched}get isUntouched(){return !!this._cd?.control?.untouched}get isPristine(){return this._cd?.control?._pristine?.(),!!this._cd?.control?.pristine}get isDirty(){return !!this._cd?.control?.dirty}get isValid(){return this._cd?.control?._status?.(),!!this._cd?.control?.valid}get isInvalid(){return !!this._cd?.control?.invalid}get isPending(){return !!this._cd?.control?.pending}get isSubmitted(){return this._cd?._submitted?.(),!!this._cd?.submitted}};var jr=(()=>{class i extends ke{constructor(e){super(e);}static \u0275fac=function(n){return new(n||i)(Sr(P,2))};static \u0275dir=ME({type:i,selectors:[["","formControlName",""],["","ngModel",""],["","formControl",""]],hostVars:14,hostBindings:function(n,r){n&2&&fh("ng-untouched",r.isUntouched)("ng-touched",r.isTouched)("ng-pristine",r.isPristine)("ng-dirty",r.isDirty)("ng-valid",r.isValid)("ng-invalid",r.isInvalid)("ng-pending",r.isPending);},standalone:false,features:[Hp]})}return i})(),Gr=(()=>{class i extends ke{constructor(e){super(e);}static \u0275fac=function(n){return new(n||i)(Sr(C,10))};static \u0275dir=ME({type:i,selectors:[["","formGroupName",""],["","formArrayName",""],["","ngModelGroup",""],["","formGroup",""],["","formArray",""],["form",3,"ngNoForm",""],["","ngForm",""]],hostVars:16,hostBindings:function(n,r){n&2&&fh("ng-untouched",r.isUntouched)("ng-touched",r.isTouched)("ng-pristine",r.isPristine)("ng-dirty",r.isDirty)("ng-valid",r.isValid)("ng-invalid",r.isInvalid)("ng-pending",r.isPending)("ng-submitted",r.isSubmitted);},standalone:false,features:[Hp]})}return i})(),ne=class extends ie{constructor(t,e,n){super(at(e),st(n,e)),this.controls=t,this._initObservables(),this._setUpdateStrategy(e),this._setUpControls(),this.updateValueAndValidity({onlySelf:true,emitEvent:!!this.asyncValidator});}controls;registerControl(t,e){let n=this._find(t);return n||(this.controls[t]=e,e.setParent(this),e._registerOnCollectionChange(this._onCollectionChange),e)}addControl(t,e,n={}){this.registerControl(t,e),this.updateValueAndValidity({emitEvent:n.emitEvent}),this._onCollectionChange();}removeControl(t,e={}){let n=this._find(t);n&&n._registerOnCollectionChange(()=>{}),delete this.controls[t],this.updateValueAndValidity({emitEvent:e.emitEvent}),this._onCollectionChange();}setControl(t,e,n={}){let r=this._find(t);r&&r._registerOnCollectionChange(()=>{}),delete this.controls[t],e&&this.registerControl(t,e),this.updateValueAndValidity({emitEvent:n.emitEvent}),this._onCollectionChange();}contains(t){return this._find(t)?.enabled===true}setValue(t,e={}){mw(()=>{_i(this,true,t),Object.keys(t).forEach(n=>{gi(this,true,n),this.controls[n].setValue(t[n],{onlySelf:true,emitEvent:e.emitEvent});}),this.updateValueAndValidity(e);});}patchValue(t,e={}){t!=null&&(Object.keys(t).forEach(n=>{let r=this._find(n);r&&r.patchValue(t[n],{onlySelf:true,emitEvent:e.emitEvent});}),this.updateValueAndValidity(e));}reset(t={},e={}){this._forEachChild((n,r)=>{n.reset(t?t[r]:null,W$1(q({},e),{onlySelf:true}));}),this._updatePristine(e,this),this._updateTouched(e,this),this.updateValueAndValidity(e),e?.emitEvent!==false&&this._events.next(new W(this));}getRawValue(){return this._reduceChildren({},(t,e,n)=>(t[n]=e.getRawValue(),t))}_syncPendingControls(){let t=this._reduceChildren(false,(e,n)=>n._syncPendingControls()?true:e);return t&&this.updateValueAndValidity({onlySelf:true}),t}_forEachChild(t){Object.keys(this.controls).forEach(e=>{let n=this.controls[e];n&&t(n,e);});}_setUpControls(){this._forEachChild(t=>{t.setParent(this),t._registerOnCollectionChange(this._onCollectionChange);});}_updateValue(){this.value=this._reduceValue();}_anyControls(t){for(let[e,n]of Object.entries(this.controls))if(this.contains(e)&&t(n))return  true;return  false}_reduceValue(){let t={};return this._reduceChildren(t,(e,n,r)=>((n.enabled||this.disabled)&&(e[r]=n.value),e))}_reduceChildren(t,e){let n=t;return this._forEachChild((r,o)=>{n=e(n,r,o);}),n}_allControlsDisabled(){for(let t of Object.keys(this.controls))if(this.controls[t].enabled)return  false;return Object.keys(this.controls).length>0||this.disabled}_find(t){return vi(this.controls,t)?this.controls[t]:null}};var et=class extends ne{};var Rn={provide:C,useExisting:vo(()=>ct)},fe=Promise.resolve(),ct=(()=>{class i extends C{callSetDisabledState;get submitted(){return mw(this.submittedReactive)}_submitted=gw(()=>this.submittedReactive());submittedReactive=Vo(false);_directives=new Set;form;ngSubmit=new Ue;options;constructor(e,n,r){super(),this.callSetDisabledState=r,this.form=new ne({},rt(e),ot(n));}ngAfterViewInit(){this._setUpdateStrategy();}get formDirective(){return this}get control(){return this.form}get path(){return []}get controls(){return this.form.controls}addControl(e){fe.then(()=>{let n=this._findContainer(e.path);e.control=n.registerControl(e.name,e.control),e._setupWithForm(this.callSetDisabledState),e.control.updateValueAndValidity({emitEvent:false}),this._directives.add(e);});}getControl(e){return this.form.get(e.path)}removeControl(e){fe.then(()=>{this._findContainer(e.path)?.removeControl(e.name),this._directives.delete(e);});}addFormGroup(e){fe.then(()=>{let n=this._findContainer(e.path),r=new ne({});Di(r,e),n.registerControl(e.name,r),r.updateValueAndValidity({emitEvent:false});});}removeFormGroup(e){fe.then(()=>{this._findContainer(e.path)?.removeControl?.(e.name);});}getFormGroup(e){return this.form.get(e.path)}updateModel(e,n){fe.then(()=>{this.form.get(e.path).setValue(n);});}setValue(e){this.control.setValue(e);}onSubmit(e){return this.submittedReactive.set(true),Mi(this.form,this._directives),this.ngSubmit.emit(e),this.form._events.next(new Re(this.control)),e?.target?.method==="dialog"}onReset(){this.resetForm();}resetForm(e=void 0){this.form.reset(e),this.submittedReactive.set(false);}_setUpdateStrategy(){this.options&&this.options.updateOn!=null&&(this.form._updateOn=this.options.updateOn);}_findContainer(e){return e.pop(),e.length?this.form.get(e):this.form}static \u0275fac=function(n){return new(n||i)(Sr(L,10),Sr(re,10),Sr(_e,8))};static \u0275dir=ME({type:i,selectors:[["form",3,"ngNoForm","",3,"formGroup","",3,"formArray",""],["ng-form"],["","ngForm",""]],hostBindings:function(n,r){n&1&&nh("submit",function(a){return r.onSubmit(a)})("reset",function(){return r.onReset()});},inputs:{options:[0,"ngFormOptions","options"]},outputs:{ngSubmit:"ngSubmit"},exportAs:["ngForm"],standalone:false,features:[JD([Rn]),Hp]})}return i})();function Xt(i,t){let e=i.indexOf(t);e>-1&&i.splice(e,1);}function Kt(i){return typeof i=="object"&&i!==null&&Object.keys(i).length===2&&"value"in i&&"disabled"in i}var Ve=class extends ie{defaultValue=null;_onChange=[];_pendingValue;_pendingChange=false;constructor(t=null,e,n){super(at(e),st(n,e)),this._applyFormState(t),this._setUpdateStrategy(e),this._initObservables(),this.updateValueAndValidity({onlySelf:true,emitEvent:!!this.asyncValidator}),Le(e)&&(e.nonNullable||e.initialValueIsDefault)&&(Kt(t)?this.defaultValue=t.value:this.defaultValue=t);}setValue(t,e={}){mw(()=>{this.value=this._pendingValue=t,this._onChange.length&&e.emitModelToViewChange!==false&&this._onChange.forEach(n=>n(this.value,e.emitViewToModelChange!==false)),this.updateValueAndValidity(e);});}patchValue(t,e={}){this.setValue(t,e);}reset(t=this.defaultValue,e={}){this._applyFormState(t),this.markAsPristine(e),this.markAsUntouched(e),this.setValue(this.value,e),e.overwriteDefaultValue&&(this.defaultValue=this.value),this._pendingChange=false,e?.emitEvent!==false&&this._events.next(new W(this));}_updateValue(){}_anyControls(t){return  false}_allControlsDisabled(){return this.disabled}registerOnChange(t){this._onChange.push(t);}_unregisterOnChange(t){Xt(this._onChange,t);}registerOnDisabledChange(t){this._onDisabledChange.push(t);}_unregisterOnDisabledChange(t){Xt(this._onDisabledChange,t);}_forEachChild(t){}_syncPendingControls(){return this.updateOn==="submit"&&(this._pendingDirty&&this.markAsDirty(),this._pendingTouched&&this.markAsTouched(),this._pendingChange)?(this.setValue(this._pendingValue,{onlySelf:true,emitModelToViewChange:false}),true):false}_applyFormState(t){Kt(t)?(this.value=this._pendingValue=t.value,t.disabled?this.disable({onlySelf:true,emitEvent:false}):this.enable({onlySelf:true,emitEvent:false})):this.value=this._pendingValue=t;}};var On=i=>i instanceof Ve,Nn=(()=>{class i extends C{_parent;ngOnInit(){this._checkParentType(),this.formDirective.addFormGroup(this);}ngOnDestroy(){this.formDirective?.removeFormGroup(this);}get control(){return this.formDirective.getFormGroup(this)}get path(){return lt(this.name==null?this.name:this.name.toString(),this._parent)}get formDirective(){return this._parent?this._parent.formDirective:null}_checkParentType(){}static \u0275fac=(()=>{let e;return function(r){return (e||(e=dy(i)))(r||i)}})();static \u0275dir=ME({type:i,standalone:false,features:[Hp]})}return i})();var qr=(()=>{class i{static \u0275fac=function(n){return new(n||i)};static \u0275dir=ME({type:i,selectors:[["form",3,"ngNoForm","",3,"ngNativeValidate",""]],hostAttrs:["novalidate",""],standalone:false})}return i})(),Tn={provide:Pe,useExisting:vo(()=>kn),multi:true},kn=(()=>{class i extends ti{writeValue(e){let n=e??"";this.setProperty("value",n);}registerOnChange(e){this.onChange=n=>{e(n==""?null:parseFloat(n));};}static \u0275fac=(()=>{let e;return function(r){return (e||(e=dy(i)))(r||i)}})();static \u0275dir=ME({type:i,selectors:[["input","type","number","formControlName","",3,"ngNoCva",""],["input","type","number","formControl","",3,"ngNoCva",""],["input","type","number","ngModel","",3,"ngNoCva",""]],hostBindings:function(n,r){n&1&&nh("input",function(a){return r.onChange(a.target.value)})("blur",function(){return r.onTouched()});},standalone:false,features:[JD([Tn]),Hp]})}return i})();var tt=class extends ie{constructor(t,e,n){super(at(e),st(n,e)),this.controls=t,this._initObservables(),this._setUpdateStrategy(e),this._setUpControls(),this.updateValueAndValidity({onlySelf:true,emitEvent:!!this.asyncValidator});}controls;at(t){return this.controls[this._adjustIndex(t)]}push(t,e={}){Array.isArray(t)?t.forEach(n=>{this.controls.push(n),this._registerControl(n);}):(this.controls.push(t),this._registerControl(t)),this.updateValueAndValidity({emitEvent:e.emitEvent}),this._onCollectionChange();}insert(t,e,n={}){this.controls.splice(t,0,e),this._registerControl(e),this.updateValueAndValidity({emitEvent:n.emitEvent});}removeAt(t,e={}){let n=this._adjustIndex(t);n<0&&(n=0),this.controls[n]&&this.controls[n]._registerOnCollectionChange(()=>{}),this.controls.splice(n,1),this.updateValueAndValidity({emitEvent:e.emitEvent});}setControl(t,e,n={}){let r=this._adjustIndex(t);r<0&&(r=0),this.controls[r]&&this.controls[r]._registerOnCollectionChange(()=>{}),this.controls.splice(r,1),e&&(this.controls.splice(r,0,e),this._registerControl(e)),this.updateValueAndValidity({emitEvent:n.emitEvent}),this._onCollectionChange();}get length(){return this.controls.length}setValue(t,e={}){mw(()=>{_i(this,false,t),t.forEach((n,r)=>{gi(this,false,r),this.at(r).setValue(n,{onlySelf:true,emitEvent:e.emitEvent});}),this.updateValueAndValidity(e);});}patchValue(t,e={}){t!=null&&(t.forEach((n,r)=>{this.at(r)&&this.at(r).patchValue(n,{onlySelf:true,emitEvent:e.emitEvent});}),this.updateValueAndValidity(e));}reset(t=[],e={}){this._forEachChild((n,r)=>{n.reset(t[r],W$1(q({},e),{onlySelf:true}));}),this._updatePristine(e,this),this._updateTouched(e,this),this.updateValueAndValidity(e),e?.emitEvent!==false&&this._events.next(new W(this));}getRawValue(){return this.controls.map(t=>t.getRawValue())}clear(t={}){this.controls.length<1||(this._forEachChild(e=>e._registerOnCollectionChange(()=>{})),this.controls.splice(0),this.updateValueAndValidity({emitEvent:t.emitEvent}));}_adjustIndex(t){return t<0?t+this.length:t}_syncPendingControls(){let t=this.controls.reduce((e,n)=>n._syncPendingControls()?true:e,false);return t&&this.updateValueAndValidity({onlySelf:true}),t}_forEachChild(t){this.controls.forEach((e,n)=>{t(e,n);});}_updateValue(){this.value=this.controls.filter(t=>t.enabled||this.disabled).map(t=>t.value);}_anyControls(t){return this.controls.some(e=>e.enabled&&t(e))}_setUpControls(){this._forEachChild(t=>this._registerControl(t));}_allControlsDisabled(){for(let t of this.controls)if(t.enabled)return  false;return this.controls.length>0||this.disabled}_registerControl(t){t.setParent(this),t._registerOnCollectionChange(this._onCollectionChange);}_find(t){return this.at(t)??null}};var Vi=(()=>{class i extends C{callSetDisabledState;get submitted(){return mw(this._submittedReactive)}set submitted(e){this._submittedReactive.set(e);}_submitted=gw(()=>this._submittedReactive());_submittedReactive=Vo(false);_oldForm;_onCollectionChange=()=>this._updateDomValue();directives=[];constructor(e,n,r){super(),this.callSetDisabledState=r,this._setValidators(e),this._setAsyncValidators(n);}ngOnChanges(e){this.onChanges(e);}ngOnDestroy(){this.onDestroy();}onChanges(e){this._checkFormPresent(),e.hasOwnProperty("form")&&(this._updateValidators(),this._updateDomValue(),this._updateRegistrations(),this._oldForm=this.form);}onDestroy(){this.form&&(Te(this.form,this),this.form._onCollectionChange===this._onCollectionChange&&this.form._registerOnCollectionChange(()=>{}));}get formDirective(){return this}get path(){return []}addControl(e){let n=this.form.get(e.path);return e._setupWithForm(n,this.callSetDisabledState),n.updateValueAndValidity({emitEvent:false}),this.directives.push(e),n}getControl(e){return this.form.get(e.path)}removeControl(e){Oe(e.control||null,e,false),In(this.directives,e);}addFormGroup(e){this._setUpFormContainer(e);}removeFormGroup(e){this._cleanUpFormContainer(e);}getFormGroup(e){return this.form.get(e.path)}getFormArray(e){return this.form.get(e.path)}addFormArray(e){this._setUpFormContainer(e);}removeFormArray(e){this._cleanUpFormContainer(e);}updateModel(e,n){this.form.get(e.path).setValue(n);}onReset(){this.resetForm();}resetForm(e=void 0,n={}){this.form.reset(e,n),this._submittedReactive.set(false);}onSubmit(e){return this.submitted=true,Mi(this.form,this.directives),this.ngSubmit.emit(e),this.form._events.next(new Re(this.control)),e?.target?.method==="dialog"}_updateDomValue(){this.directives.forEach(e=>{let n=e.control,r=this.form.get(e.path);n!==r&&(Oe(n||null,e),On(r)&&e._setupWithForm(r,this.callSetDisabledState));}),this.form._updateTreeValidity({emitEvent:false});}_setUpFormContainer(e){let n=this.form.get(e.path);Di(n,e),n.updateValueAndValidity({emitEvent:false});}_cleanUpFormContainer(e){let n=this.form?.get(e.path);n&&An(n,e)&&n.updateValueAndValidity({emitEvent:false});}_updateRegistrations(){this.form._registerOnCollectionChange(this._onCollectionChange),this._oldForm?._registerOnCollectionChange(()=>{});}_updateValidators(){dt(this.form,this),this._oldForm&&Te(this._oldForm,this);}_checkFormPresent(){this.form;}static \u0275fac=function(n){return new(n||i)(Sr(L,10),Sr(re,10),Sr(_e,8))};static \u0275dir=ME({type:i,features:[Hp,Gm]})}return i})();var ut=new x(""),Pn={provide:P,useExisting:vo(()=>Ln)},Ln=(()=>{class i extends P{_ngModelWarningConfig;callSetDisabledState;viewModel;form;set isDisabled(e){}model;update=new Ue;static _ngModelWarningSentOnce=false;_ngModelWarningSent=false;constructor(e,n,r,o,a,m,B){super(B,m,r),this._ngModelWarningConfig=o,this.callSetDisabledState=a,this._setValidators(e),this._setAsyncValidators(n);}ngOnChanges(e){if(this._isControlChanged(e)){let n=e.form.previousValue;n&&(Oe(n,this,false),this.removeParseErrorsValidator(n)),this.isCustomControlBased?this.setupCustomControl():(this.valueAccessor??=this.selectedValueAccessor,xi(this.form,this,this.callSetDisabledState)),this.form.updateValueAndValidity({emitEvent:false});}Fi(e,this.viewModel)&&(this.form.setValue(this.model),this.viewModel=this.model);}ngOnDestroy(){this.form&&Oe(this.form,this,false);}get path(){return []}get control(){return this.form}viewToModelUpdate(e){this.viewModel=e,this.update.emit(e);}_isControlChanged(e){return e.hasOwnProperty("form")}\u0275ngControlCreate(e){super.ngControlCreate(e);}\u0275ngControlUpdate(e){super.ngControlUpdate(e,true);}static \u0275fac=function(n){return new(n||i)(Sr(L,10),Sr(re,10),Sr(Pe,10),Sr(ut,8),Sr(_e,8),Sr(_I,8),Sr(ge,8))};static \u0275dir=ME({type:i,selectors:[["","formControl",""]],inputs:{form:[0,"formControl","form"],isDisabled:[0,"disabled","isDisabled"],model:[0,"ngModel","model"]},outputs:{update:"ngModelChange"},exportAs:["ngForm"],standalone:false,features:[JD([Pn,wi]),Hp,Gm,xE(null)]})}return i})(),Bn={provide:C,useExisting:vo(()=>Ai)},Ai=(()=>{class i extends Nn{name=null;constructor(e,n,r){super(),this._parent=e,this._setValidators(n),this._setAsyncValidators(r);}_checkParentType(){Si(this._parent);}static \u0275fac=function(n){return new(n||i)(Sr(C,13),Sr(L,10),Sr(re,10))};static \u0275dir=ME({type:i,selectors:[["","formGroupName",""]],inputs:{name:[0,"formGroupName","name"]},standalone:false,features:[JD([Bn]),Hp]})}return i})(),zn={provide:C,useExisting:vo(()=>Ei)},Ei=(()=>{class i extends C{_parent;name=null;constructor(e,n,r){super(),this._parent=e,this._setValidators(n),this._setAsyncValidators(r);}ngOnInit(){Si(this._parent),this.formDirective.addFormArray(this);}ngOnDestroy(){this.formDirective?.removeFormArray(this);}get control(){return this.formDirective.getFormArray(this)}get formDirective(){return this._parent?this._parent.formDirective:null}get path(){return lt(this.name==null?this.name:this.name.toString(),this._parent)}static \u0275fac=function(n){return new(n||i)(Sr(C,13),Sr(L,10),Sr(re,10))};static \u0275dir=ME({type:i,selectors:[["","formArrayName",""]],inputs:{name:[0,"formArrayName","name"]},standalone:false,features:[JD([zn]),Hp]})}return i})();function Si(i){return !(i instanceof Ai)&&!(i instanceof Vi)&&!(i instanceof Ei)}var Hn={provide:P,useExisting:vo(()=>jn)},jn=(()=>{class i extends P{_ngModelWarningConfig;_added=false;viewModel;control;name=null;set isDisabled(e){}model;update=new Ue;static _ngModelWarningSentOnce=false;_ngModelWarningSent=false;constructor(e,n,r,o,a,m,B){super(B,m,o),this._ngModelWarningConfig=a,this._parent=e,this._setValidators(n),this._setAsyncValidators(r);}_setupWithForm(e,n){this.control=e,this.isCustomControlBased?this.setupCustomControl():(this.valueAccessor??=this.selectedValueAccessor,xi(e,this,n));}ngOnChanges(e){this._added||this._setUpControl(),Fi(e,this.viewModel)&&(this.viewModel=this.model,this.formDirective.updateModel(this,this.model));}ngOnDestroy(){this.formDirective?.removeControl(this);}viewToModelUpdate(e){this.viewModel=e,this.update.emit(e);}get path(){return lt(this.name==null?this.name:this.name.toString(),this._parent)}get formDirective(){return this._parent?this._parent.formDirective:null}_setUpControl(){this.control=this.formDirective.addControl(this),this._added=true;}\u0275ngControlCreate(e){super.ngControlCreate(e);}\u0275ngControlUpdate(e){this.isCustomControlBased&&(this._added||this._setUpControl(),super.ngControlUpdate(e,true));}static \u0275fac=function(n){return new(n||i)(Sr(C,13),Sr(L,10),Sr(re,10),Sr(Pe,10),Sr(ut,8),Sr(_I,8),Sr(ge,8))};static \u0275dir=ME({type:i,selectors:[["","formControlName",""]],inputs:{name:[0,"formControlName","name"],isDisabled:[0,"disabled","isDisabled"],model:[0,"ngModel","model"]},outputs:{update:"ngModelChange"},standalone:false,features:[JD([Hn,wi]),Hp,Gm,xE(null)]})}return i})();var Gn={provide:C,useExisting:vo(()=>ft)},ft=(()=>{class i extends Vi{form=null;ngSubmit=new Ue;get control(){return this.form}static \u0275fac=(()=>{let e;return function(r){return (e||(e=dy(i)))(r||i)}})();static \u0275dir=ME({type:i,selectors:[["","formGroup",""]],hostBindings:function(n,r){n&1&&nh("submit",function(a){return r.onSubmit(a)})("reset",function(){return r.onReset()});},inputs:{form:[0,"formGroup","form"]},outputs:{ngSubmit:"ngSubmit"},exportAs:["ngForm"],standalone:false,features:[JD([Gn]),Hp]})}return i})();var Ii=(()=>{class i{static \u0275fac=function(n){return new(n||i)};static \u0275mod=CE({type:i});static \u0275inj=cu({})}return i})();function Jt(i){return !!i&&(i.asyncValidators!==void 0||i.validators!==void 0||i.updateOn!==void 0)}var Wr=(()=>{class i{useNonNullable=false;get nonNullable(){let e=new i;return e.useNonNullable=true,e}group(e,n=null){let r=this._reduceControls(e),o={};return Jt(n)?o=n:n!==null&&(o.validators=n.validator,o.asyncValidators=n.asyncValidator),new ne(r,o)}record(e,n=null){let r=this._reduceControls(e);return new et(r,n)}control(e,n,r){let o={};return this.useNonNullable?(Jt(n)?o=n:(o.validators=n,o.asyncValidators=r),new Ve(e,W$1(q({},o),{nonNullable:true}))):new Ve(e,n,r)}array(e,n,r){let o=e.map(a=>this._createControl(a));return new tt(o,n,r)}_reduceControls(e){let n={};return Object.keys(e).forEach(r=>{n[r]=this._createControl(e[r]);}),n}_createControl(e){if(e instanceof Ve)return e;if(e instanceof ie)return e;if(Array.isArray(e)){let n=e[0],r=e.length>1?e[1]:null,o=e.length>2?e[2]:null;return this.control(n,r,o)}else return this.control(e)}static \u0275fac=function(n){return new(n||i)};static \u0275prov=Er({token:i,factory:i.\u0275fac})}return i})();var $r=(()=>{class i{static withConfig(e){return {ngModule:i,providers:[{provide:_e,useValue:e.callSetDisabledState??ze}]}}static \u0275fac=function(n){return new(n||i)};static \u0275mod=CE({type:i});static \u0275inj=cu({imports:[Ii]})}return i})(),Zr=(()=>{class i{static withConfig(e){return {ngModule:i,providers:[{provide:ut,useValue:e.warnOnNgModelWithFormControl??"always"},{provide:_e,useValue:e.callSetDisabledState??ze}]}}static \u0275fac=function(n){return new(n||i)};static \u0275mod=CE({type:i});static \u0275inj=cu({imports:[Ii]})}return i})();var mt=class{_box;_destroyed=new ne$1;_resizeSubject=new ne$1;_resizeObserver;_elementObservables=new Map;constructor(t){this._box=t,typeof ResizeObserver<"u"&&(this._resizeObserver=new ResizeObserver(e=>this._resizeSubject.next(e)));}observe(t){return this._elementObservables.has(t)||this._elementObservables.set(t,new _$1(e=>{let n=this._resizeSubject.subscribe(e);return this._resizeObserver?.observe(t,{box:this._box}),()=>{this._resizeObserver?.unobserve(t),n.unsubscribe(),this._elementObservables.delete(t);}}).pipe(Yt$1(e=>e.some(n=>n.target===t)),Vg({bufferSize:1,refCount:true}),Bl(this._destroyed))),this._elementObservables.get(t)}destroy(){this._destroyed.next(),this._destroyed.complete(),this._resizeSubject.complete(),this._elementObservables.clear();}},Ri=(()=>{class i{_cleanupErrorListener;_observers=new Map;_ngZone=T(Ne$1);constructor(){}ngOnDestroy(){for(let[,e]of this._observers)e.destroy();this._observers.clear(),this._cleanupErrorListener?.();}observe(e,n){let r=n?.box||"content-box";return this._observers.has(r)||this._observers.set(r,new mt(r)),this._observers.get(r).observe(e)}static \u0275fac=function(n){return new(n||i)};static \u0275prov=Er({token:i,factory:i.\u0275fac})}return i})();var Un=["notch"],qn=["*"],Oi=["iconPrefixContainer"],Ni=["textPrefixContainer"],Ti=["iconSuffixContainer"],ki=["textSuffixContainer"],Wn=["textField"],$n=["*",[["mat-label"]],[["","matPrefix",""],["","matIconPrefix",""]],[["","matTextPrefix",""]],[["","matTextSuffix",""]],[["","matSuffix",""],["","matIconSuffix",""]],[["mat-error"],["","matError",""]],[["mat-hint",3,"align","end"]],[["mat-hint","align","end"]]],Zn=["*","mat-label","[matPrefix], [matIconPrefix]","[matTextPrefix]","[matTextSuffix]","[matSuffix], [matIconSuffix]","mat-error, [matError]","mat-hint:not([align='end'])","mat-hint[align='end']"];function Qn(i,t){i&1&&Yp(0,"span",21);}function Yn(i,t){if(i&1&&(yi$1(0,"label",20),gD(1,1),zE(2,Qn,1,0,"span",21),Vc()),i&2){let e=fD(2);Zp("floating",e._shouldLabelFloat())("monitorResize",e._hasOutline())("id",e._labelId),Qp("for",e._control.disableAutomaticLabeling?null:e._control.id),qv(2),QE(!e.hideRequiredMarker&&e._control.required?2:-1);}}function Xn(i,t){if(i&1&&zE(0,Yn,3,5,"label",20),i&2){let e=fD();QE(e._hasFloatingLabel()?0:-1);}}function Kn(i,t){i&1&&Yp(0,"div",7);}function Jn(i,t){}function er(i,t){if(i&1&&$p(0,Jn,0,0,"ng-template",13),i&2){fD(2);let e=ED(1);Zp("ngTemplateOutlet",e);}}function tr(i,t){if(i&1&&(yi$1(0,"div",9),zE(1,er,1,1,null,13),Vc()),i&2){let e=fD();Zp("matFormFieldNotchedOutlineOpen",e._shouldLabelFloat()),qv(),QE(e._forceDisplayInfixLabel()?-1:1);}}function ir(i,t){i&1&&(yi$1(0,"div",10,2),gD(2,2),Vc());}function nr(i,t){i&1&&(yi$1(0,"div",11,3),gD(2,3),Vc());}function rr(i,t){}function or(i,t){if(i&1&&$p(0,rr,0,0,"ng-template",13),i&2){fD();let e=ED(1);Zp("ngTemplateOutlet",e);}}function ar(i,t){i&1&&(yi$1(0,"div",14,4),gD(2,4),Vc());}function sr(i,t){i&1&&(yi$1(0,"div",15,5),gD(2,5),Vc());}function lr(i,t){i&1&&Yp(0,"div",16);}function dr(i,t){i&1&&(yi$1(0,"div",18),gD(1,6),Vc());}function cr(i,t){if(i&1&&(yi$1(0,"mat-hint",22),$D(1),Vc()),i&2){let e=fD(2);Zp("id",e._hintLabelId),qv(),Ih(e.hintLabel);}}function ur(i,t){if(i&1&&(yi$1(0,"div",19),zE(1,cr,2,2,"mat-hint",22),gD(2,7),Yp(3,"div",23),gD(4,8),Vc()),i&2){let e=fD();qv(),QE(e.hintLabel?1:-1);}}var ht=(()=>{class i{static \u0275fac=function(n){return new(n||i)};static \u0275dir=ME({type:i,selectors:[["mat-label"]]})}return i})(),Gi=new x("MatError"),fr=(()=>{class i{id=T(ye).getId("mat-mdc-error-");static \u0275fac=function(n){return new(n||i)};static \u0275dir=ME({type:i,selectors:[["mat-error"],["","matError",""]],hostAttrs:[1,"mat-mdc-form-field-error","mat-mdc-form-field-bottom-align"],hostVars:1,hostBindings:function(n,r){n&2&&eh("id",r.id);},inputs:{id:"id"},features:[JD([{provide:Gi,useExisting:i}])]})}return i})(),pt=(()=>{class i{align="start";id=T(ye).getId("mat-mdc-hint-");static \u0275fac=function(n){return new(n||i)};static \u0275dir=ME({type:i,selectors:[["mat-hint"]],hostAttrs:[1,"mat-mdc-form-field-hint","mat-mdc-form-field-bottom-align"],hostVars:4,hostBindings:function(n,r){n&2&&(eh("id",r.id),Qp("align",null),fh("mat-mdc-form-field-hint-end",r.align==="end"));},inputs:{align:"align",id:"id"}})}return i})(),mr=new x("MatPrefix");var Ui=new x("MatSuffix"),hr=(()=>{class i{set _isTextSelector(e){this._isText=true;}_isText=false;static \u0275fac=function(n){return new(n||i)};static \u0275dir=ME({type:i,selectors:[["","matSuffix",""],["","matIconSuffix",""],["","matTextSuffix",""]],inputs:{_isTextSelector:[0,"matTextSuffix","_isTextSelector"]},features:[JD([{provide:Ui,useExisting:i}])]})}return i})(),qi=new x("FloatingLabelParent"),Pi=(()=>{class i{_elementRef=T(Dr);get floating(){return this._floating}set floating(e){this._floating=e,this.monitorResize&&this._handleResize();}_floating=false;get monitorResize(){return this._monitorResize}set monitorResize(e){this._monitorResize=e,this._monitorResize?this._subscribeToResize():this._resizeSubscription.unsubscribe();}_monitorResize=false;_resizeObserver=T(Ri);_ngZone=T(Ne$1);_parent=T(qi);_resizeSubscription=new j;ngOnDestroy(){this._resizeSubscription.unsubscribe();}getWidth(){return pr(this._elementRef.nativeElement)}get element(){return this._elementRef.nativeElement}_handleResize(){setTimeout(()=>this._parent._handleLabelResized());}_subscribeToResize(){this._resizeSubscription.unsubscribe(),this._ngZone.runOutsideAngular(()=>{this._resizeSubscription=this._resizeObserver.observe(this._elementRef.nativeElement,{box:"border-box"}).subscribe(()=>this._handleResize());});}static \u0275fac=function(n){return new(n||i)};static \u0275dir=ME({type:i,selectors:[["label","matFormFieldFloatingLabel",""]],hostAttrs:[1,"mdc-floating-label","mat-mdc-floating-label"],hostVars:2,hostBindings:function(n,r){n&2&&fh("mdc-floating-label--float-above",r.floating);},inputs:{floating:"floating",monitorResize:"monitorResize"}})}return i})();function pr(i){let t=i;if(t.offsetParent!==null)return t.scrollWidth;let e=t.cloneNode(true);e.style.setProperty("position","absolute"),e.style.setProperty("transform","translate(-9999px, -9999px)"),document.documentElement.appendChild(e);let n=e.scrollWidth;return e.remove(),n}var Li="mdc-line-ripple--active",He="mdc-line-ripple--deactivating",Bi=(()=>{class i{_elementRef=T(Dr);_cleanupTransitionEnd;constructor(){let e=T(Ne$1),n=T(_I);e.runOutsideAngular(()=>{this._cleanupTransitionEnd=n.listen(this._elementRef.nativeElement,"transitionend",this._handleTransitionEnd);});}activate(){let e=this._elementRef.nativeElement.classList;e.remove(He),e.add(Li);}deactivate(){this._elementRef.nativeElement.classList.add(He);}_handleTransitionEnd=e=>{let n=this._elementRef.nativeElement.classList,r=n.contains(He);e.propertyName==="opacity"&&r&&n.remove(Li,He);};ngOnDestroy(){this._cleanupTransitionEnd();}static \u0275fac=function(n){return new(n||i)};static \u0275dir=ME({type:i,selectors:[["div","matFormFieldLineRipple",""]],hostAttrs:[1,"mdc-line-ripple"]})}return i})(),zi=(()=>{class i{_elementRef=T(Dr);_ngZone=T(Ne$1);open=false;_notch;ngAfterViewInit(){let e=this._elementRef.nativeElement,n=e.querySelector(".mdc-floating-label");n?(e.classList.add("mdc-notched-outline--upgraded"),typeof requestAnimationFrame=="function"&&(n.style.transitionDuration="0s",this._ngZone.runOutsideAngular(()=>{requestAnimationFrame(()=>n.style.transitionDuration="");}))):e.classList.add("mdc-notched-outline--no-label");}_setNotchWidth(e){let n=this._notch.nativeElement;!this.open||!e?n.style.width="":n.style.width=`calc(${e}px * var(--mat-mdc-form-field-floating-label-scale, 0.75) + 9px)`;}_setMaxWidth(e){this._notch.nativeElement.style.setProperty("--mat-form-field-notch-max-width",`calc(100% - ${e}px)`);}static \u0275fac=function(n){return new(n||i)};static \u0275cmp=wE({type:i,selectors:[["div","matFormFieldNotchedOutline",""]],viewQuery:function(n,r){if(n&1&&sh(Un,5),n&2){let o;yD(o=vD())&&(r._notch=o.first);}},hostAttrs:[1,"mdc-notched-outline"],hostVars:2,hostBindings:function(n,r){n&2&&fh("mdc-notched-outline--notched",r.open);},inputs:{open:[0,"matFormFieldNotchedOutlineOpen","open"]},ngContentSelectors:qn,decls:5,vars:0,consts:[["notch",""],[1,"mat-mdc-notch-piece","mdc-notched-outline__leading"],[1,"mat-mdc-notch-piece","mdc-notched-outline__notch"],[1,"mat-mdc-notch-piece","mdc-notched-outline__trailing"]],template:function(n,r){n&1&&(hD(),Kp(0,"div",1),Hc(1,"div",2,0),gD(3),Bc(),Kp(4,"div",3));},encapsulation:2})}return i})(),gt=(()=>{class i{value=null;stateChanges;id;placeholder;ngControl=null;focused=false;empty=false;shouldLabelFloat=false;required=false;disabled=false;errorState=false;controlType;autofilled;userAriaDescribedBy;disableAutomaticLabeling;describedByIds;static \u0275fac=function(n){return new(n||i)};static \u0275dir=ME({type:i})}return i})();var _t=new x("MatFormField"),gr=new x("MAT_FORM_FIELD_DEFAULT_OPTIONS"),Hi="fill",_r="auto",ji="fixed",vr="translateY(-50%)",Wi=(()=>{class i{_elementRef=T(Dr);_changeDetectorRef=T(F0);_platform=T(_);_idGenerator=T(ye);_ngZone=T(Ne$1);_defaults=T(gr,{optional:true});_currentDirection;_textField;_iconPrefixContainer;_textPrefixContainer;_iconSuffixContainer;_textSuffixContainer;_floatingLabel;_notchedOutline;_lineRipple;_iconPrefixContainerSignal=k0("iconPrefixContainer");_textPrefixContainerSignal=k0("textPrefixContainer");_iconSuffixContainerSignal=k0("iconSuffixContainer");_textSuffixContainerSignal=k0("textSuffixContainer");_prefixSuffixContainers=gw(()=>[this._iconPrefixContainerSignal(),this._textPrefixContainerSignal(),this._iconSuffixContainerSignal(),this._textSuffixContainerSignal()].map(e=>e?.nativeElement).filter(e=>e!==void 0));_formFieldControl;_prefixChildren;_suffixChildren;_errorChildren;_hintChildren;_labelChild=P0(ht);get hideRequiredMarker(){return this._hideRequiredMarker}set hideRequiredMarker(e){this._hideRequiredMarker=Us(e);}_hideRequiredMarker=false;color="primary";get floatLabel(){return this._floatLabel||this._defaults?.floatLabel||_r}set floatLabel(e){e!==this._floatLabel&&(this._floatLabel=e,this._changeDetectorRef.markForCheck());}_floatLabel;get appearance(){return this._appearanceSignal()}set appearance(e){let n=e||this._defaults?.appearance||Hi;this._appearanceSignal.set(n);}_appearanceSignal=Vo(Hi);get subscriptSizing(){return this._subscriptSizing||this._defaults?.subscriptSizing||ji}set subscriptSizing(e){this._subscriptSizing=e||this._defaults?.subscriptSizing||ji;}_subscriptSizing=null;get hintLabel(){return this._hintLabel}set hintLabel(e){this._hintLabel=e,this._processHints();}_hintLabel="";_hasIconPrefix=false;_hasTextPrefix=false;_hasIconSuffix=false;_hasTextSuffix=false;_labelId=this._idGenerator.getId("mat-mdc-form-field-label-");_hintLabelId=this._idGenerator.getId("mat-mdc-hint-");_describedByIds;get _control(){return this._explicitFormFieldControl||this._formFieldControl}set _control(e){this._explicitFormFieldControl=e;}_destroyed=new ne$1;_isFocused=null;_explicitFormFieldControl;_previousControl=null;_previousControlValidatorFn=null;_stateChanges;_valueChanges;_describedByChanges;_outlineLabelOffsetResizeObserver=null;_animationsDisabled=tt$1();constructor(){let e=this._defaults,n=T(Er$1);e&&(e.appearance&&(this.appearance=e.appearance),this._hideRequiredMarker=!!e?.hideRequiredMarker,e.color&&(this.color=e.color)),nd(()=>this._currentDirection=n.valueSignal()),this._syncOutlineLabelOffset();}ngAfterViewInit(){this._updateFocusState(),this._animationsDisabled||this._ngZone.runOutsideAngular(()=>{setTimeout(()=>{this._elementRef.nativeElement.classList.add("mat-form-field-animations-enabled");},300);}),this._changeDetectorRef.detectChanges();}ngAfterContentInit(){this._assertFormFieldControl(),this._initializeSubscript(),this._initializePrefixAndSuffix();}ngAfterContentChecked(){this._assertFormFieldControl(),this._control!==this._previousControl&&(this._initializeControl(this._previousControl),this._control.ngControl&&this._control.ngControl.control&&(this._previousControlValidatorFn=this._control.ngControl.control.validator),this._previousControl=this._control),this._control.ngControl&&this._control.ngControl.control&&this._control.ngControl.control.validator!==this._previousControlValidatorFn&&this._changeDetectorRef.markForCheck();}ngOnDestroy(){this._outlineLabelOffsetResizeObserver?.disconnect(),this._stateChanges?.unsubscribe(),this._valueChanges?.unsubscribe(),this._describedByChanges?.unsubscribe(),this._destroyed.next(),this._destroyed.complete();}getLabelId=gw(()=>this._hasFloatingLabel()?this._labelId:null);getConnectedOverlayOrigin(){return this._textField||this._elementRef}_animateAndLockLabel(){this._hasFloatingLabel()&&(this.floatLabel="always");}_initializeControl(e){let n=this._control,r="mat-mdc-form-field-type-";e&&this._elementRef.nativeElement.classList.remove(r+e.controlType),n.controlType&&this._elementRef.nativeElement.classList.add(r+n.controlType),this._stateChanges?.unsubscribe(),this._stateChanges=n.stateChanges.subscribe(()=>{this._updateFocusState(),this._changeDetectorRef.markForCheck();}),this._describedByChanges?.unsubscribe(),this._describedByChanges=n.stateChanges.pipe(Hl([void 0,void 0]),be(()=>[n.errorState,n.userAriaDescribedBy]),jg(),Yt$1(([[o,a],[m,B]])=>o!==m||a!==B)).subscribe(()=>this._syncDescribedByIds()),this._valueChanges?.unsubscribe(),n.ngControl&&n.ngControl.valueChanges&&(this._valueChanges=n.ngControl.valueChanges.pipe(Bl(this._destroyed)).subscribe(()=>this._changeDetectorRef.markForCheck()));}_checkPrefixAndSuffixTypes(){this._hasIconPrefix=!!this._prefixChildren.find(e=>!e._isText),this._hasTextPrefix=!!this._prefixChildren.find(e=>e._isText),this._hasIconSuffix=!!this._suffixChildren.find(e=>!e._isText),this._hasTextSuffix=!!this._suffixChildren.find(e=>e._isText);}_initializePrefixAndSuffix(){this._checkPrefixAndSuffixTypes(),Rg(this._prefixChildren.changes,this._suffixChildren.changes).subscribe(()=>{this._checkPrefixAndSuffixTypes(),this._changeDetectorRef.markForCheck();});}_initializeSubscript(){this._hintChildren.changes.subscribe(()=>{this._processHints(),this._changeDetectorRef.markForCheck();}),this._errorChildren.changes.subscribe(()=>{this._syncDescribedByIds(),this._changeDetectorRef.markForCheck();}),this._validateHints(),this._syncDescribedByIds();}_assertFormFieldControl(){this._control;}_updateFocusState(){let e=this._control.focused;e&&!this._isFocused?(this._isFocused=true,this._lineRipple?.activate()):!e&&(this._isFocused||this._isFocused===null)&&(this._isFocused=false,this._lineRipple?.deactivate()),this._elementRef.nativeElement.classList.toggle("mat-focused",e),this._textField?.nativeElement.classList.toggle("mdc-text-field--focused",e);}_syncOutlineLabelOffset(){B0({earlyRead:()=>{if(this._appearanceSignal()!=="outline")return this._outlineLabelOffsetResizeObserver?.disconnect(),null;if(globalThis.ResizeObserver){this._outlineLabelOffsetResizeObserver||=new globalThis.ResizeObserver(()=>{this._writeOutlinedLabelStyles(this._getOutlinedLabelOffset());});for(let e of this._prefixSuffixContainers())this._outlineLabelOffsetResizeObserver.observe(e,{box:"border-box"});}return this._getOutlinedLabelOffset()},write:e=>this._writeOutlinedLabelStyles(e())});}_shouldAlwaysFloat(){return this.floatLabel==="always"}_hasOutline(){return this.appearance==="outline"}_forceDisplayInfixLabel(){return !this._platform.isBrowser&&this._prefixChildren.length&&!this._shouldLabelFloat()}_hasFloatingLabel=gw(()=>!!this._labelChild());_shouldLabelFloat(){return this._hasFloatingLabel()?this._control.shouldLabelFloat||this._shouldAlwaysFloat():false}_shouldForward(e){let n=this._control?this._control.ngControl:null;return n&&n[e]}_getSubscriptMessageType(){return this._errorChildren&&this._errorChildren.length>0&&this._control.errorState?"error":"hint"}_handleLabelResized(){this._refreshOutlineNotchWidth();}_refreshOutlineNotchWidth(){!this._hasOutline()||!this._floatingLabel||!this._shouldLabelFloat()?this._notchedOutline?._setNotchWidth(0):this._notchedOutline?._setNotchWidth(this._floatingLabel.getWidth());}_processHints(){this._validateHints(),this._syncDescribedByIds();}_validateHints(){this._hintChildren;}_syncDescribedByIds(){if(this._control){let e=[];if(this._control.userAriaDescribedBy&&typeof this._control.userAriaDescribedBy=="string"&&e.push(...this._control.userAriaDescribedBy.split(" ")),this._getSubscriptMessageType()==="hint"){let o=this._hintChildren?this._hintChildren.find(m=>m.align==="start"):null,a=this._hintChildren?this._hintChildren.find(m=>m.align==="end"):null;o?e.push(o.id):this._hintLabel&&e.push(this._hintLabelId),a&&e.push(a.id);}else this._errorChildren&&e.push(...this._errorChildren.map(o=>o.id));let n=this._control.describedByIds,r;if(n){let o=this._describedByIds||e;r=e.concat(n.filter(a=>a&&!o.includes(a)));}else r=e;this._control.setDescribedByIds(r),this._describedByIds=e;}}_getOutlinedLabelOffset(){if(!this._hasOutline()||!this._floatingLabel)return null;if(!this._iconPrefixContainer&&!this._textPrefixContainer)return ["",null];if(!this._isAttachedToDom())return null;let e=this._iconPrefixContainer?.nativeElement,n=this._textPrefixContainer?.nativeElement,r=this._iconSuffixContainer?.nativeElement,o=this._textSuffixContainer?.nativeElement,a=e?.getBoundingClientRect().width??0,m=n?.getBoundingClientRect().width??0,B=r?.getBoundingClientRect().width??0,$=o?.getBoundingClientRect().width??0,Yi=this._currentDirection==="rtl"?"-1":"1",Xi=`${a+m}px`,Ki=`calc(${Yi} * (${Xi} + var(--mat-mdc-form-field-label-offset-x, 0px)))`,Ji=`var(--mat-mdc-form-field-label-transform, ${vr} translateX(${Ki}))`,en=a+m+B+$;return [Ji,en]}_writeOutlinedLabelStyles(e){if(e!==null){let[n,r]=e;this._floatingLabel&&(this._floatingLabel.element.style.transform=n),r!==null&&this._notchedOutline?._setMaxWidth(r);}}_isAttachedToDom(){let e=this._elementRef.nativeElement;if(e.getRootNode){let n=e.getRootNode();return n&&n!==e}return document.documentElement.contains(e)}static \u0275fac=function(n){return new(n||i)};static \u0275cmp=wE({type:i,selectors:[["mat-form-field"]],contentQueries:function(n,r,o){if(n&1&&(ah(o,r._labelChild,ht,5),ih(o,gt,5)(o,mr,5)(o,Ui,5)(o,Gi,5)(o,pt,5)),n&2){ID();let a;yD(a=vD())&&(r._formFieldControl=a.first),yD(a=vD())&&(r._prefixChildren=a),yD(a=vD())&&(r._suffixChildren=a),yD(a=vD())&&(r._errorChildren=a),yD(a=vD())&&(r._hintChildren=a);}},viewQuery:function(n,r){if(n&1&&(ch(r._iconPrefixContainerSignal,Oi,5)(r._textPrefixContainerSignal,Ni,5)(r._iconSuffixContainerSignal,Ti,5)(r._textSuffixContainerSignal,ki,5),sh(Wn,5)(Oi,5)(Ni,5)(Ti,5)(ki,5)(Pi,5)(zi,5)(Bi,5)),n&2){ID(4);let o;yD(o=vD())&&(r._textField=o.first),yD(o=vD())&&(r._iconPrefixContainer=o.first),yD(o=vD())&&(r._textPrefixContainer=o.first),yD(o=vD())&&(r._iconSuffixContainer=o.first),yD(o=vD())&&(r._textSuffixContainer=o.first),yD(o=vD())&&(r._floatingLabel=o.first),yD(o=vD())&&(r._notchedOutline=o.first),yD(o=vD())&&(r._lineRipple=o.first);}},hostAttrs:[1,"mat-mdc-form-field"],hostVars:38,hostBindings:function(n,r){n&2&&fh("mat-mdc-form-field-label-always-float",r._shouldAlwaysFloat())("mat-mdc-form-field-has-icon-prefix",r._hasIconPrefix)("mat-mdc-form-field-has-icon-suffix",r._hasIconSuffix)("mat-form-field-invalid",r._control.errorState)("mat-form-field-disabled",r._control.disabled)("mat-form-field-autofilled",r._control.autofilled)("mat-form-field-appearance-fill",r.appearance=="fill")("mat-form-field-appearance-outline",r.appearance=="outline")("mat-form-field-hide-placeholder",r._hasFloatingLabel()&&!r._shouldLabelFloat())("mat-primary",r.color!=="accent"&&r.color!=="warn")("mat-accent",r.color==="accent")("mat-warn",r.color==="warn")("ng-untouched",r._shouldForward("untouched"))("ng-touched",r._shouldForward("touched"))("ng-pristine",r._shouldForward("pristine"))("ng-dirty",r._shouldForward("dirty"))("ng-valid",r._shouldForward("valid"))("ng-invalid",r._shouldForward("invalid"))("ng-pending",r._shouldForward("pending"));},inputs:{hideRequiredMarker:"hideRequiredMarker",color:"color",floatLabel:"floatLabel",appearance:"appearance",subscriptSizing:"subscriptSizing",hintLabel:"hintLabel"},exportAs:["matFormField"],features:[JD([{provide:_t,useExisting:i},{provide:qi,useExisting:i}])],ngContentSelectors:Zn,decls:18,vars:21,consts:[["labelTemplate",""],["textField",""],["iconPrefixContainer",""],["textPrefixContainer",""],["textSuffixContainer",""],["iconSuffixContainer",""],[1,"mat-mdc-text-field-wrapper","mdc-text-field",3,"click"],[1,"mat-mdc-form-field-focus-overlay"],[1,"mat-mdc-form-field-flex"],["matFormFieldNotchedOutline","",3,"matFormFieldNotchedOutlineOpen"],[1,"mat-mdc-form-field-icon-prefix"],[1,"mat-mdc-form-field-text-prefix"],[1,"mat-mdc-form-field-infix"],[3,"ngTemplateOutlet"],[1,"mat-mdc-form-field-text-suffix"],[1,"mat-mdc-form-field-icon-suffix"],["matFormFieldLineRipple",""],["aria-atomic","true","aria-live","polite",1,"mat-mdc-form-field-subscript-wrapper","mat-mdc-form-field-bottom-align"],[1,"mat-mdc-form-field-error-wrapper"],[1,"mat-mdc-form-field-hint-wrapper"],["matFormFieldFloatingLabel","",3,"floating","monitorResize","id"],["aria-hidden","true",1,"mat-mdc-form-field-required-marker","mdc-floating-label--required"],[3,"id"],[1,"mat-mdc-form-field-hint-spacer"]],template:function(n,r){if(n&1&&(hD($n),$p(0,Xn,1,1,"ng-template",null,0,uw),yi$1(2,"div",6,1),nh("click",function(a){return r._control.onContainerClick(a)}),zE(4,Kn,1,0,"div",7),yi$1(5,"div",8),zE(6,tr,2,2,"div",9),zE(7,ir,3,0,"div",10),zE(8,nr,3,0,"div",11),yi$1(9,"div",12),zE(10,or,1,1,null,13),gD(11),Vc(),zE(12,ar,3,0,"div",14),zE(13,sr,3,0,"div",15),Vc(),zE(14,lr,1,0,"div",16),Vc(),yi$1(15,"div",17),zE(16,dr,2,0,"div",18)(17,ur,5,1,"div",19),Vc()),n&2){let o;qv(2),fh("mdc-text-field--filled",!r._hasOutline())("mdc-text-field--outlined",r._hasOutline())("mdc-text-field--no-label",!r._hasFloatingLabel())("mdc-text-field--disabled",r._control.disabled)("mdc-text-field--invalid",r._control.errorState),qv(2),QE(!r._hasOutline()&&!r._control.disabled?4:-1),qv(2),QE(r._hasOutline()?6:-1),qv(),QE(r._hasIconPrefix?7:-1),qv(),QE(r._hasTextPrefix?8:-1),qv(2),QE(!r._hasOutline()||r._forceDisplayInfixLabel()?10:-1),qv(2),QE(r._hasTextSuffix?12:-1),qv(),QE(r._hasIconSuffix?13:-1),qv(),QE(r._hasOutline()?-1:14),qv(),fh("mat-mdc-form-field-subscript-dynamic-size",r.subscriptSizing==="dynamic");let a=r._getSubscriptMessageType();qv(),QE((o=a)==="error"?16:o==="hint"?17:-1);}},dependencies:[Pi,zi,jn$1,Bi,pt],styles:[`.mdc-text-field {
  display: inline-flex;
  align-items: baseline;
  padding: 0 16px;
  position: relative;
  box-sizing: border-box;
  overflow: hidden;
  will-change: opacity, transform, color;
  border-top-left-radius: 4px;
  border-top-right-radius: 4px;
  border-bottom-right-radius: 0;
  border-bottom-left-radius: 0;
}

.mdc-text-field__input {
  width: 100%;
  min-width: 0;
  border: none;
  border-radius: 0;
  background: none;
  padding: 0;
  -moz-appearance: none;
  -webkit-appearance: none;
  height: 28px;
}
.mdc-text-field__input::-webkit-calendar-picker-indicator, .mdc-text-field__input::-webkit-search-cancel-button {
  display: none;
}
.mdc-text-field__input::-ms-clear {
  display: none;
}
.mdc-text-field__input:focus {
  outline: none;
}
.mdc-text-field__input:invalid {
  box-shadow: none;
}
.mdc-text-field__input::placeholder {
  opacity: 0;
}
.mdc-text-field__input::-moz-placeholder {
  opacity: 0;
}
.mdc-text-field__input::-webkit-input-placeholder {
  opacity: 0;
}
.mdc-text-field__input:-ms-input-placeholder {
  opacity: 0;
}
.mdc-text-field--no-label .mdc-text-field__input::placeholder, .mdc-text-field--focused .mdc-text-field__input::placeholder {
  opacity: 1;
}
.mdc-text-field--no-label .mdc-text-field__input::-moz-placeholder, .mdc-text-field--focused .mdc-text-field__input::-moz-placeholder {
  opacity: 1;
}
.mdc-text-field--no-label .mdc-text-field__input::-webkit-input-placeholder, .mdc-text-field--focused .mdc-text-field__input::-webkit-input-placeholder {
  opacity: 1;
}
.mdc-text-field--no-label .mdc-text-field__input:-ms-input-placeholder, .mdc-text-field--focused .mdc-text-field__input:-ms-input-placeholder {
  opacity: 1;
}
.mdc-text-field--disabled:not(.mdc-text-field--no-label) .mdc-text-field__input.mat-mdc-input-disabled-interactive::placeholder {
  opacity: 0;
}
.mdc-text-field--disabled:not(.mdc-text-field--no-label) .mdc-text-field__input.mat-mdc-input-disabled-interactive::-moz-placeholder {
  opacity: 0;
}
.mdc-text-field--disabled:not(.mdc-text-field--no-label) .mdc-text-field__input.mat-mdc-input-disabled-interactive::-webkit-input-placeholder {
  opacity: 0;
}
.mdc-text-field--disabled:not(.mdc-text-field--no-label) .mdc-text-field__input.mat-mdc-input-disabled-interactive:-ms-input-placeholder {
  opacity: 0;
}
.mdc-text-field--outlined .mdc-text-field__input, .mdc-text-field--filled.mdc-text-field--no-label .mdc-text-field__input {
  height: 100%;
}
.mdc-text-field--outlined .mdc-text-field__input {
  display: flex;
  border: none !important;
  background-color: transparent;
}
.mdc-text-field--disabled .mdc-text-field__input {
  pointer-events: auto;
}
.mdc-text-field--filled:not(.mdc-text-field--disabled) .mdc-text-field__input {
  color: var(--mat-form-field-filled-input-text-color, var(--mat-sys-on-surface));
  caret-color: var(--mat-form-field-filled-caret-color, var(--mat-sys-primary));
}
.mdc-text-field--filled:not(.mdc-text-field--disabled) .mdc-text-field__input::placeholder {
  color: var(--mat-form-field-filled-input-text-placeholder-color, var(--mat-sys-on-surface-variant));
}
.mdc-text-field--filled:not(.mdc-text-field--disabled) .mdc-text-field__input::-moz-placeholder {
  color: var(--mat-form-field-filled-input-text-placeholder-color, var(--mat-sys-on-surface-variant));
}
.mdc-text-field--filled:not(.mdc-text-field--disabled) .mdc-text-field__input::-webkit-input-placeholder {
  color: var(--mat-form-field-filled-input-text-placeholder-color, var(--mat-sys-on-surface-variant));
}
.mdc-text-field--filled:not(.mdc-text-field--disabled) .mdc-text-field__input:-ms-input-placeholder {
  color: var(--mat-form-field-filled-input-text-placeholder-color, var(--mat-sys-on-surface-variant));
}
.mdc-text-field--outlined:not(.mdc-text-field--disabled) .mdc-text-field__input {
  color: var(--mat-form-field-outlined-input-text-color, var(--mat-sys-on-surface));
  caret-color: var(--mat-form-field-outlined-caret-color, var(--mat-sys-primary));
}
.mdc-text-field--outlined:not(.mdc-text-field--disabled) .mdc-text-field__input::placeholder {
  color: var(--mat-form-field-outlined-input-text-placeholder-color, var(--mat-sys-on-surface-variant));
}
.mdc-text-field--outlined:not(.mdc-text-field--disabled) .mdc-text-field__input::-moz-placeholder {
  color: var(--mat-form-field-outlined-input-text-placeholder-color, var(--mat-sys-on-surface-variant));
}
.mdc-text-field--outlined:not(.mdc-text-field--disabled) .mdc-text-field__input::-webkit-input-placeholder {
  color: var(--mat-form-field-outlined-input-text-placeholder-color, var(--mat-sys-on-surface-variant));
}
.mdc-text-field--outlined:not(.mdc-text-field--disabled) .mdc-text-field__input:-ms-input-placeholder {
  color: var(--mat-form-field-outlined-input-text-placeholder-color, var(--mat-sys-on-surface-variant));
}
.mdc-text-field--filled.mdc-text-field--invalid:not(.mdc-text-field--disabled) .mdc-text-field__input {
  caret-color: var(--mat-form-field-filled-error-caret-color, var(--mat-sys-error));
}
.mdc-text-field--outlined.mdc-text-field--invalid:not(.mdc-text-field--disabled) .mdc-text-field__input {
  caret-color: var(--mat-form-field-outlined-error-caret-color, var(--mat-sys-error));
}
.mdc-text-field--filled.mdc-text-field--disabled .mdc-text-field__input {
  color: var(--mat-form-field-filled-disabled-input-text-color, color-mix(in srgb, var(--mat-sys-on-surface) 38%, transparent));
}
.mdc-text-field--outlined.mdc-text-field--disabled .mdc-text-field__input {
  color: var(--mat-form-field-outlined-disabled-input-text-color, color-mix(in srgb, var(--mat-sys-on-surface) 38%, transparent));
}
@media (forced-colors: active) {
  .mdc-text-field--disabled .mdc-text-field__input {
    background-color: Window;
  }
}

.mdc-text-field--filled {
  height: 56px;
  border-bottom-right-radius: 0;
  border-bottom-left-radius: 0;
  border-top-left-radius: var(--mat-form-field-filled-container-shape, var(--mat-sys-corner-extra-small));
  border-top-right-radius: var(--mat-form-field-filled-container-shape, var(--mat-sys-corner-extra-small));
}
.mdc-text-field--filled:not(.mdc-text-field--disabled) {
  background-color: var(--mat-form-field-filled-container-color, var(--mat-sys-surface-variant));
}
.mdc-text-field--filled.mdc-text-field--disabled {
  background-color: var(--mat-form-field-filled-disabled-container-color, color-mix(in srgb, var(--mat-sys-on-surface) 4%, transparent));
}

.mdc-text-field--outlined {
  height: 56px;
  overflow: visible;
  padding-right: max(16px, var(--mat-form-field-outlined-container-shape, var(--mat-sys-corner-extra-small)));
  padding-left: max(16px, var(--mat-form-field-outlined-container-shape, var(--mat-sys-corner-extra-small)) + 4px);
}
[dir=rtl] .mdc-text-field--outlined {
  padding-right: max(16px, var(--mat-form-field-outlined-container-shape, var(--mat-sys-corner-extra-small)) + 4px);
  padding-left: max(16px, var(--mat-form-field-outlined-container-shape, var(--mat-sys-corner-extra-small)));
}

.mdc-floating-label {
  position: absolute;
  left: 0;
  transform-origin: left top;
  line-height: 1.15rem;
  text-align: left;
  text-overflow: ellipsis;
  white-space: nowrap;
  cursor: text;
  overflow: hidden;
  will-change: transform;
}
[dir=rtl] .mdc-floating-label {
  right: 0;
  left: auto;
  transform-origin: right top;
  text-align: right;
}
.mdc-text-field .mdc-floating-label {
  top: 50%;
  transform: translateY(-50%);
  pointer-events: none;
}
.mdc-notched-outline .mdc-floating-label {
  display: inline-block;
  position: relative;
  max-width: 100%;
}
.mdc-text-field--outlined .mdc-floating-label {
  left: 4px;
  right: auto;
}
[dir=rtl] .mdc-text-field--outlined .mdc-floating-label {
  left: auto;
  right: 4px;
}
.mdc-text-field--filled .mdc-floating-label {
  left: 16px;
  right: auto;
}
[dir=rtl] .mdc-text-field--filled .mdc-floating-label {
  left: auto;
  right: 16px;
}
.mdc-text-field--disabled .mdc-floating-label {
  cursor: default;
}
@media (forced-colors: active) {
  .mdc-text-field--disabled .mdc-floating-label {
    z-index: 1;
  }
}
.mdc-text-field--filled.mdc-text-field--no-label .mdc-floating-label {
  display: none;
}
.mdc-text-field--filled:not(.mdc-text-field--disabled) .mdc-floating-label {
  color: var(--mat-form-field-filled-label-text-color, var(--mat-sys-on-surface-variant));
}
.mdc-text-field--filled:not(.mdc-text-field--disabled).mdc-text-field--focused .mdc-floating-label {
  color: var(--mat-form-field-filled-focus-label-text-color, var(--mat-sys-primary));
}
.mdc-text-field--filled:not(.mdc-text-field--disabled):not(.mdc-text-field--focused):hover .mdc-floating-label {
  color: var(--mat-form-field-filled-hover-label-text-color, var(--mat-sys-on-surface-variant));
}
.mdc-text-field--filled.mdc-text-field--disabled .mdc-floating-label {
  color: var(--mat-form-field-filled-disabled-label-text-color, color-mix(in srgb, var(--mat-sys-on-surface) 38%, transparent));
}
.mdc-text-field--filled:not(.mdc-text-field--disabled).mdc-text-field--invalid .mdc-floating-label {
  color: var(--mat-form-field-filled-error-label-text-color, var(--mat-sys-error));
}
.mdc-text-field--filled:not(.mdc-text-field--disabled).mdc-text-field--invalid.mdc-text-field--focused .mdc-floating-label {
  color: var(--mat-form-field-filled-error-focus-label-text-color, var(--mat-sys-error));
}
.mdc-text-field--filled:not(.mdc-text-field--disabled).mdc-text-field--invalid:not(.mdc-text-field--disabled):hover .mdc-floating-label {
  color: var(--mat-form-field-filled-error-hover-label-text-color, var(--mat-sys-on-error-container));
}
.mdc-text-field--filled .mdc-floating-label {
  font-family: var(--mat-form-field-filled-label-text-font, var(--mat-sys-body-large-font));
  font-size: var(--mat-form-field-filled-label-text-size, var(--mat-sys-body-large-size));
  font-weight: var(--mat-form-field-filled-label-text-weight, var(--mat-sys-body-large-weight));
  letter-spacing: var(--mat-form-field-filled-label-text-tracking, var(--mat-sys-body-large-tracking));
}
.mdc-text-field--outlined:not(.mdc-text-field--disabled) .mdc-floating-label {
  color: var(--mat-form-field-outlined-label-text-color, var(--mat-sys-on-surface-variant));
}
.mdc-text-field--outlined:not(.mdc-text-field--disabled).mdc-text-field--focused .mdc-floating-label {
  color: var(--mat-form-field-outlined-focus-label-text-color, var(--mat-sys-primary));
}
.mdc-text-field--outlined:not(.mdc-text-field--disabled):not(.mdc-text-field--focused):hover .mdc-floating-label {
  color: var(--mat-form-field-outlined-hover-label-text-color, var(--mat-sys-on-surface));
}
.mdc-text-field--outlined.mdc-text-field--disabled .mdc-floating-label {
  color: var(--mat-form-field-outlined-disabled-label-text-color, color-mix(in srgb, var(--mat-sys-on-surface) 38%, transparent));
}
.mdc-text-field--outlined:not(.mdc-text-field--disabled).mdc-text-field--invalid .mdc-floating-label {
  color: var(--mat-form-field-outlined-error-label-text-color, var(--mat-sys-error));
}
.mdc-text-field--outlined:not(.mdc-text-field--disabled).mdc-text-field--invalid.mdc-text-field--focused .mdc-floating-label {
  color: var(--mat-form-field-outlined-error-focus-label-text-color, var(--mat-sys-error));
}
.mdc-text-field--outlined:not(.mdc-text-field--disabled).mdc-text-field--invalid:not(.mdc-text-field--disabled):hover .mdc-floating-label {
  color: var(--mat-form-field-outlined-error-hover-label-text-color, var(--mat-sys-on-error-container));
}
.mdc-text-field--outlined .mdc-floating-label {
  font-family: var(--mat-form-field-outlined-label-text-font, var(--mat-sys-body-large-font));
  font-size: var(--mat-form-field-outlined-label-text-size, var(--mat-sys-body-large-size));
  font-weight: var(--mat-form-field-outlined-label-text-weight, var(--mat-sys-body-large-weight));
  letter-spacing: var(--mat-form-field-outlined-label-text-tracking, var(--mat-sys-body-large-tracking));
}

.mdc-floating-label--float-above {
  cursor: auto;
  transform: translateY(-106%) scale(0.75);
}
.mdc-text-field--filled .mdc-floating-label--float-above {
  transform: translateY(-106%) scale(0.75);
}
.mdc-text-field--outlined .mdc-floating-label--float-above {
  transform: translateY(-37.25px) scale(1);
  font-size: 0.75rem;
}
.mdc-notched-outline .mdc-floating-label--float-above {
  text-overflow: clip;
}
.mdc-notched-outline--upgraded .mdc-floating-label--float-above {
  max-width: 133.3333333333%;
}
.mdc-text-field--outlined.mdc-notched-outline--upgraded .mdc-floating-label--float-above, .mdc-text-field--outlined .mdc-notched-outline--upgraded .mdc-floating-label--float-above {
  transform: translateY(-34.75px) scale(0.75);
}
.mdc-text-field--outlined.mdc-notched-outline--upgraded .mdc-floating-label--float-above, .mdc-text-field--outlined .mdc-notched-outline--upgraded .mdc-floating-label--float-above {
  font-size: 1rem;
}

.mdc-floating-label--required:not(.mdc-floating-label--hide-required-marker)::after {
  margin-left: 1px;
  margin-right: 0;
  content: "*";
}
[dir=rtl] .mdc-floating-label--required:not(.mdc-floating-label--hide-required-marker)::after {
  margin-left: 0;
  margin-right: 1px;
}

.mdc-notched-outline {
  display: flex;
  position: absolute;
  top: 0;
  right: 0;
  left: 0;
  box-sizing: border-box;
  width: 100%;
  max-width: 100%;
  height: 100%;
  text-align: left;
  pointer-events: none;
}
[dir=rtl] .mdc-notched-outline {
  text-align: right;
}
.mdc-text-field--outlined .mdc-notched-outline {
  z-index: 1;
}

.mat-mdc-notch-piece {
  box-sizing: border-box;
  height: 100%;
  pointer-events: none;
  border: none;
  border-top: 1px solid;
  border-bottom: 1px solid;
}
.mdc-text-field--focused .mat-mdc-notch-piece {
  border-width: 2px;
}
.mdc-text-field--outlined:not(.mdc-text-field--disabled) .mat-mdc-notch-piece {
  border-color: var(--mat-form-field-outlined-outline-color, var(--mat-sys-outline));
  border-width: var(--mat-form-field-outlined-outline-width, 1px);
}
.mdc-text-field--outlined:not(.mdc-text-field--disabled):not(.mdc-text-field--focused):hover .mat-mdc-notch-piece {
  border-color: var(--mat-form-field-outlined-hover-outline-color, var(--mat-sys-on-surface));
}
.mdc-text-field--outlined:not(.mdc-text-field--disabled).mdc-text-field--focused .mat-mdc-notch-piece {
  border-color: var(--mat-form-field-outlined-focus-outline-color, var(--mat-sys-primary));
}
.mdc-text-field--outlined.mdc-text-field--disabled .mat-mdc-notch-piece {
  border-color: var(--mat-form-field-outlined-disabled-outline-color, color-mix(in srgb, var(--mat-sys-on-surface) 12%, transparent));
}
.mdc-text-field--outlined:not(.mdc-text-field--disabled).mdc-text-field--invalid .mat-mdc-notch-piece {
  border-color: var(--mat-form-field-outlined-error-outline-color, var(--mat-sys-error));
}
.mdc-text-field--outlined:not(.mdc-text-field--disabled).mdc-text-field--invalid:not(.mdc-text-field--focused):hover .mdc-notched-outline .mat-mdc-notch-piece {
  border-color: var(--mat-form-field-outlined-error-hover-outline-color, var(--mat-sys-on-error-container));
}
.mdc-text-field--outlined:not(.mdc-text-field--disabled).mdc-text-field--invalid.mdc-text-field--focused .mat-mdc-notch-piece {
  border-color: var(--mat-form-field-outlined-error-focus-outline-color, var(--mat-sys-error));
}
.mdc-text-field--outlined:not(.mdc-text-field--disabled).mdc-text-field--focused .mdc-notched-outline .mat-mdc-notch-piece {
  border-width: var(--mat-form-field-outlined-focus-outline-width, 2px);
}

.mdc-notched-outline__leading {
  border-left: 1px solid;
  border-right: none;
  border-top-right-radius: 0;
  border-bottom-right-radius: 0;
  border-top-left-radius: var(--mat-form-field-outlined-container-shape, var(--mat-sys-corner-extra-small));
  border-bottom-left-radius: var(--mat-form-field-outlined-container-shape, var(--mat-sys-corner-extra-small));
}
.mdc-text-field--outlined .mdc-notched-outline .mdc-notched-outline__leading {
  width: max(12px, var(--mat-form-field-outlined-container-shape, var(--mat-sys-corner-extra-small)));
}
[dir=rtl] .mdc-notched-outline__leading {
  border-left: none;
  border-right: 1px solid;
  border-bottom-left-radius: 0;
  border-top-left-radius: 0;
  border-top-right-radius: var(--mat-form-field-outlined-container-shape, var(--mat-sys-corner-extra-small));
  border-bottom-right-radius: var(--mat-form-field-outlined-container-shape, var(--mat-sys-corner-extra-small));
}

.mdc-notched-outline__trailing {
  flex-grow: 1;
  border-left: none;
  border-right: 1px solid;
  border-top-left-radius: 0;
  border-bottom-left-radius: 0;
  border-top-right-radius: var(--mat-form-field-outlined-container-shape, var(--mat-sys-corner-extra-small));
  border-bottom-right-radius: var(--mat-form-field-outlined-container-shape, var(--mat-sys-corner-extra-small));
}
[dir=rtl] .mdc-notched-outline__trailing {
  border-left: 1px solid;
  border-right: none;
  border-top-right-radius: 0;
  border-bottom-right-radius: 0;
  border-top-left-radius: var(--mat-form-field-outlined-container-shape, var(--mat-sys-corner-extra-small));
  border-bottom-left-radius: var(--mat-form-field-outlined-container-shape, var(--mat-sys-corner-extra-small));
}

.mdc-notched-outline__notch {
  flex: 0 0 auto;
  width: auto;
}
.mdc-text-field--outlined .mdc-notched-outline .mdc-notched-outline__notch {
  max-width: min(var(--mat-form-field-notch-max-width, 100%), calc(100% - max(12px, var(--mat-form-field-outlined-container-shape, var(--mat-sys-corner-extra-small))) * 2));
}
.mdc-text-field--outlined .mdc-notched-outline--notched .mdc-notched-outline__notch {
  max-width: min(100%, calc(100% - max(12px, var(--mat-form-field-outlined-container-shape, var(--mat-sys-corner-extra-small))) * 2));
}
.mdc-text-field--outlined .mdc-notched-outline--notched .mdc-notched-outline__notch {
  padding-top: 1px;
}
.mdc-text-field--focused.mdc-text-field--outlined .mdc-notched-outline--notched .mdc-notched-outline__notch {
  padding-top: 2px;
}
.mdc-notched-outline--notched .mdc-notched-outline__notch {
  padding-left: 0;
  padding-right: 8px;
  border-top: none;
}
[dir=rtl] .mdc-notched-outline--notched .mdc-notched-outline__notch {
  padding-left: 8px;
  padding-right: 0;
}
.mdc-notched-outline--no-label .mdc-notched-outline__notch {
  display: none;
}

.mdc-line-ripple::before, .mdc-line-ripple::after {
  position: absolute;
  bottom: 0;
  left: 0;
  width: 100%;
  border-bottom-style: solid;
  content: "";
}
.mdc-line-ripple::before {
  z-index: 1;
  border-bottom-width: var(--mat-form-field-filled-active-indicator-height, 1px);
}
.mdc-text-field--filled:not(.mdc-text-field--disabled) .mdc-line-ripple::before {
  border-bottom-color: var(--mat-form-field-filled-active-indicator-color, var(--mat-sys-on-surface-variant));
}
.mdc-text-field--filled:not(.mdc-text-field--disabled):not(.mdc-text-field--focused):hover .mdc-line-ripple::before {
  border-bottom-color: var(--mat-form-field-filled-hover-active-indicator-color, var(--mat-sys-on-surface));
}
.mdc-text-field--filled.mdc-text-field--disabled .mdc-line-ripple::before {
  border-bottom-color: var(--mat-form-field-filled-disabled-active-indicator-color, color-mix(in srgb, var(--mat-sys-on-surface) 38%, transparent));
}
.mdc-text-field--filled:not(.mdc-text-field--disabled).mdc-text-field--invalid .mdc-line-ripple::before {
  border-bottom-color: var(--mat-form-field-filled-error-active-indicator-color, var(--mat-sys-error));
}
.mdc-text-field--filled:not(.mdc-text-field--disabled).mdc-text-field--invalid:not(.mdc-text-field--focused):hover .mdc-line-ripple::before {
  border-bottom-color: var(--mat-form-field-filled-error-hover-active-indicator-color, var(--mat-sys-on-error-container));
}
.mdc-line-ripple::after {
  transform: scaleX(0);
  opacity: 0;
  z-index: 2;
}
.mdc-text-field--filled .mdc-line-ripple::after {
  border-bottom-width: var(--mat-form-field-filled-focus-active-indicator-height, 2px);
}
.mdc-text-field--filled:not(.mdc-text-field--disabled) .mdc-line-ripple::after {
  border-bottom-color: var(--mat-form-field-filled-focus-active-indicator-color, var(--mat-sys-primary));
}
.mdc-text-field--filled.mdc-text-field--invalid:not(.mdc-text-field--disabled) .mdc-line-ripple::after {
  border-bottom-color: var(--mat-form-field-filled-error-focus-active-indicator-color, var(--mat-sys-error));
}

.mdc-line-ripple--active::after {
  transform: scaleX(1);
  opacity: 1;
}

.mdc-line-ripple--deactivating::after {
  opacity: 0;
}

.mdc-text-field--disabled {
  pointer-events: none;
}

.mat-mdc-form-field-textarea-control {
  vertical-align: middle;
  resize: vertical;
  box-sizing: border-box;
  height: auto;
  margin: 0;
  padding: 0;
  border: none;
  overflow: auto;
}

.mat-mdc-form-field-input-control.mat-mdc-form-field-input-control {
  -moz-osx-font-smoothing: grayscale;
  -webkit-font-smoothing: antialiased;
  font: inherit;
  letter-spacing: inherit;
  text-decoration: inherit;
  text-transform: inherit;
  border: none;
}

.mat-mdc-form-field .mat-mdc-floating-label.mdc-floating-label {
  -moz-osx-font-smoothing: grayscale;
  -webkit-font-smoothing: antialiased;
  line-height: normal;
  pointer-events: all;
  will-change: auto;
}

.mat-mdc-form-field:not(.mat-form-field-disabled) .mat-mdc-floating-label.mdc-floating-label {
  cursor: inherit;
}

.mdc-text-field--no-label:not(.mdc-text-field--textarea) .mat-mdc-form-field-input-control.mdc-text-field__input,
.mat-mdc-text-field-wrapper .mat-mdc-form-field-input-control {
  height: auto;
}

.mat-mdc-text-field-wrapper .mat-mdc-form-field-input-control.mdc-text-field__input[type=color] {
  height: 23px;
}

.mat-mdc-text-field-wrapper {
  height: auto;
  flex: auto;
  will-change: auto;
}

.mat-mdc-form-field-has-icon-prefix .mat-mdc-text-field-wrapper {
  padding-left: 0;
  --mat-mdc-form-field-label-offset-x: -16px;
}

.mat-mdc-form-field-has-icon-suffix .mat-mdc-text-field-wrapper {
  padding-right: 0;
}

[dir=rtl] .mat-mdc-text-field-wrapper {
  padding-left: 16px;
  padding-right: 16px;
}
[dir=rtl] .mat-mdc-form-field-has-icon-suffix .mat-mdc-text-field-wrapper {
  padding-left: 0;
}
[dir=rtl] .mat-mdc-form-field-has-icon-prefix .mat-mdc-text-field-wrapper {
  padding-right: 0;
}

.mat-form-field-disabled .mdc-text-field__input::placeholder {
  color: var(--mat-form-field-disabled-input-text-placeholder-color, color-mix(in srgb, var(--mat-sys-on-surface) 38%, transparent));
}
.mat-form-field-disabled .mdc-text-field__input::-moz-placeholder {
  color: var(--mat-form-field-disabled-input-text-placeholder-color, color-mix(in srgb, var(--mat-sys-on-surface) 38%, transparent));
}
.mat-form-field-disabled .mdc-text-field__input::-webkit-input-placeholder {
  color: var(--mat-form-field-disabled-input-text-placeholder-color, color-mix(in srgb, var(--mat-sys-on-surface) 38%, transparent));
}
.mat-form-field-disabled .mdc-text-field__input:-ms-input-placeholder {
  color: var(--mat-form-field-disabled-input-text-placeholder-color, color-mix(in srgb, var(--mat-sys-on-surface) 38%, transparent));
}

.mat-mdc-form-field-label-always-float .mdc-text-field__input::placeholder {
  transition-delay: 40ms;
  transition-duration: 110ms;
  opacity: 1;
}

.mat-mdc-text-field-wrapper .mat-mdc-form-field-infix .mat-mdc-floating-label {
  left: auto;
  right: auto;
}

.mat-mdc-text-field-wrapper.mdc-text-field--outlined .mdc-text-field__input {
  display: inline-block;
}

.mat-mdc-form-field .mat-mdc-text-field-wrapper.mdc-text-field .mdc-notched-outline__notch {
  padding-top: 0;
}

.mat-mdc-form-field.mat-mdc-form-field.mat-mdc-form-field.mat-mdc-form-field.mat-mdc-form-field.mat-mdc-form-field .mdc-notched-outline__notch {
  border-left: 1px solid transparent;
}

[dir=rtl] .mat-mdc-form-field.mat-mdc-form-field.mat-mdc-form-field.mat-mdc-form-field.mat-mdc-form-field.mat-mdc-form-field .mdc-notched-outline__notch {
  border-left: none;
  border-right: 1px solid transparent;
}

.mat-mdc-form-field-infix {
  min-height: var(--mat-form-field-container-height, 56px);
  padding-top: var(--mat-form-field-filled-with-label-container-padding-top, 24px);
  padding-bottom: var(--mat-form-field-filled-with-label-container-padding-bottom, 8px);
}
.mdc-text-field--outlined .mat-mdc-form-field-infix, .mdc-text-field--no-label .mat-mdc-form-field-infix {
  padding-top: var(--mat-form-field-container-vertical-padding, 16px);
  padding-bottom: var(--mat-form-field-container-vertical-padding, 16px);
}

.mat-mdc-text-field-wrapper .mat-mdc-form-field-flex .mat-mdc-floating-label {
  top: calc(var(--mat-form-field-container-height, 56px) / 2);
}

.mdc-text-field--filled .mat-mdc-floating-label {
  display: var(--mat-form-field-filled-label-display, block);
}

.mat-mdc-text-field-wrapper.mdc-text-field--outlined .mdc-notched-outline--upgraded .mdc-floating-label--float-above {
  --mat-mdc-form-field-label-transform: translateY(calc(calc(6.75px + var(--mat-form-field-container-height, 56px) / 2) * -1))
    scale(var(--mat-mdc-form-field-floating-label-scale, 0.75));
  transform: var(--mat-mdc-form-field-label-transform);
}

@keyframes _mat-form-field-subscript-animation {
  from {
    opacity: 0;
    transform: translateY(-5px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
.mat-mdc-form-field-subscript-wrapper {
  box-sizing: border-box;
  width: 100%;
  position: relative;
}

.mat-mdc-form-field-hint-wrapper,
.mat-mdc-form-field-error-wrapper {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  padding: 0 16px;
  opacity: 1;
  transform: translateY(0);
  animation: _mat-form-field-subscript-animation 0ms cubic-bezier(0.55, 0, 0.55, 0.2);
}

.mat-mdc-form-field-subscript-dynamic-size .mat-mdc-form-field-hint-wrapper,
.mat-mdc-form-field-subscript-dynamic-size .mat-mdc-form-field-error-wrapper {
  position: static;
}

.mat-mdc-form-field-bottom-align::before {
  content: "";
  display: inline-block;
  height: 16px;
}

.mat-mdc-form-field-bottom-align.mat-mdc-form-field-subscript-dynamic-size::before {
  content: unset;
}

.mat-mdc-form-field-hint-end {
  order: 1;
}

.mat-mdc-form-field-hint-wrapper {
  display: flex;
}

.mat-mdc-form-field-hint-spacer {
  flex: 1 0 1em;
}

.mat-mdc-form-field-error {
  display: block;
  color: var(--mat-form-field-error-text-color, var(--mat-sys-error));
}

.mat-mdc-form-field-subscript-wrapper,
.mat-mdc-form-field-bottom-align::before {
  -moz-osx-font-smoothing: grayscale;
  -webkit-font-smoothing: antialiased;
  font-family: var(--mat-form-field-subscript-text-font, var(--mat-sys-body-small-font));
  line-height: var(--mat-form-field-subscript-text-line-height, var(--mat-sys-body-small-line-height));
  font-size: var(--mat-form-field-subscript-text-size, var(--mat-sys-body-small-size));
  letter-spacing: var(--mat-form-field-subscript-text-tracking, var(--mat-sys-body-small-tracking));
  font-weight: var(--mat-form-field-subscript-text-weight, var(--mat-sys-body-small-weight));
}

.mat-mdc-form-field-focus-overlay {
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  position: absolute;
  opacity: 0;
  pointer-events: none;
  background-color: var(--mat-form-field-state-layer-color, var(--mat-sys-on-surface));
}
.mat-mdc-text-field-wrapper:hover .mat-mdc-form-field-focus-overlay {
  opacity: var(--mat-form-field-hover-state-layer-opacity, var(--mat-sys-hover-state-layer-opacity));
}
.mat-mdc-form-field.mat-focused .mat-mdc-form-field-focus-overlay {
  opacity: var(--mat-form-field-focus-state-layer-opacity, 0);
}

select.mat-mdc-form-field-input-control {
  -moz-appearance: none;
  -webkit-appearance: none;
  background-color: transparent;
  display: inline-flex;
  box-sizing: border-box;
}
select.mat-mdc-form-field-input-control:not(:disabled) {
  cursor: pointer;
}
select.mat-mdc-form-field-input-control:not(.mat-mdc-native-select-inline) option {
  color: var(--mat-form-field-select-option-text-color, var(--mat-sys-neutral10));
}
select.mat-mdc-form-field-input-control:not(.mat-mdc-native-select-inline) option:disabled {
  color: var(--mat-form-field-select-disabled-option-text-color, color-mix(in srgb, var(--mat-sys-neutral10) 38%, transparent));
}

.mat-mdc-form-field-type-mat-native-select .mat-mdc-form-field-infix::after {
  content: "";
  width: 0;
  height: 0;
  border-left: 5px solid transparent;
  border-right: 5px solid transparent;
  border-top: 5px solid;
  position: absolute;
  right: 0;
  top: 50%;
  margin-top: -2.5px;
  pointer-events: none;
  color: var(--mat-form-field-enabled-select-arrow-color, var(--mat-sys-on-surface-variant));
}
[dir=rtl] .mat-mdc-form-field-type-mat-native-select .mat-mdc-form-field-infix::after {
  right: auto;
  left: 0;
}
.mat-mdc-form-field-type-mat-native-select.mat-focused .mat-mdc-form-field-infix::after {
  color: var(--mat-form-field-focus-select-arrow-color, var(--mat-sys-primary));
}
.mat-mdc-form-field-type-mat-native-select.mat-form-field-disabled .mat-mdc-form-field-infix::after {
  color: var(--mat-form-field-disabled-select-arrow-color, color-mix(in srgb, var(--mat-sys-on-surface) 38%, transparent));
}
.mat-mdc-form-field-type-mat-native-select .mat-mdc-form-field-input-control {
  padding-right: 15px;
}
[dir=rtl] .mat-mdc-form-field-type-mat-native-select .mat-mdc-form-field-input-control {
  padding-right: 0;
  padding-left: 15px;
}

@media (forced-colors: active) {
  .mat-form-field-appearance-fill .mat-mdc-text-field-wrapper {
    outline: solid 1px;
  }
}
@media (forced-colors: active) {
  .mat-form-field-appearance-fill.mat-form-field-disabled .mat-mdc-text-field-wrapper {
    outline-color: GrayText;
  }
}

@media (forced-colors: active) {
  .mat-form-field-appearance-fill.mat-focused .mat-mdc-text-field-wrapper {
    outline: dashed 3px;
  }
}

@media (forced-colors: active) {
  .mat-mdc-form-field.mat-focused .mdc-notched-outline {
    border: dashed 3px;
  }
}

.mat-mdc-form-field-input-control[type=date], .mat-mdc-form-field-input-control[type=datetime], .mat-mdc-form-field-input-control[type=datetime-local], .mat-mdc-form-field-input-control[type=month], .mat-mdc-form-field-input-control[type=week], .mat-mdc-form-field-input-control[type=time] {
  line-height: 1;
}
.mat-mdc-form-field-input-control::-webkit-datetime-edit {
  line-height: 1;
  padding: 0;
  margin-bottom: -2px;
}

.mat-mdc-form-field {
  --mat-mdc-form-field-floating-label-scale: 0.75;
  display: inline-flex;
  flex-direction: column;
  min-width: 0;
  text-align: left;
  -moz-osx-font-smoothing: grayscale;
  -webkit-font-smoothing: antialiased;
  font-family: var(--mat-form-field-container-text-font, var(--mat-sys-body-large-font));
  line-height: var(--mat-form-field-container-text-line-height, var(--mat-sys-body-large-line-height));
  font-size: var(--mat-form-field-container-text-size, var(--mat-sys-body-large-size));
  letter-spacing: var(--mat-form-field-container-text-tracking, var(--mat-sys-body-large-tracking));
  font-weight: var(--mat-form-field-container-text-weight, var(--mat-sys-body-large-weight));
}
.mat-mdc-form-field .mdc-text-field--outlined .mdc-floating-label--float-above {
  font-size: calc(var(--mat-form-field-outlined-label-text-populated-size) * var(--mat-mdc-form-field-floating-label-scale));
}
.mat-mdc-form-field .mdc-text-field--outlined .mdc-notched-outline--upgraded .mdc-floating-label--float-above {
  font-size: var(--mat-form-field-outlined-label-text-populated-size);
}
[dir=rtl] .mat-mdc-form-field {
  text-align: right;
}

.mat-mdc-form-field-flex {
  display: inline-flex;
  align-items: baseline;
  box-sizing: border-box;
  width: 100%;
}

.mat-mdc-text-field-wrapper {
  width: 100%;
  z-index: 0;
}

.mat-mdc-form-field-icon-prefix,
.mat-mdc-form-field-icon-suffix {
  align-self: center;
  line-height: 0;
  pointer-events: auto;
  position: relative;
  z-index: 1;
}
.mat-mdc-form-field-icon-prefix > .mat-icon,
.mat-mdc-form-field-icon-suffix > .mat-icon {
  padding: 0 12px;
  box-sizing: content-box;
}

.mat-mdc-form-field-icon-prefix {
  color: var(--mat-form-field-leading-icon-color, var(--mat-sys-on-surface-variant));
}
.mat-form-field-disabled .mat-mdc-form-field-icon-prefix {
  color: var(--mat-form-field-disabled-leading-icon-color, color-mix(in srgb, var(--mat-sys-on-surface) 38%, transparent));
}

.mat-mdc-form-field-icon-suffix {
  color: var(--mat-form-field-trailing-icon-color, var(--mat-sys-on-surface-variant));
}
.mat-form-field-disabled .mat-mdc-form-field-icon-suffix {
  color: var(--mat-form-field-disabled-trailing-icon-color, color-mix(in srgb, var(--mat-sys-on-surface) 38%, transparent));
}
.mat-form-field-invalid .mat-mdc-form-field-icon-suffix {
  color: var(--mat-form-field-error-trailing-icon-color, var(--mat-sys-error));
}
.mat-form-field-invalid:not(.mat-focused):not(.mat-form-field-disabled) .mat-mdc-text-field-wrapper:hover .mat-mdc-form-field-icon-suffix {
  color: var(--mat-form-field-error-hover-trailing-icon-color, var(--mat-sys-on-error-container));
}
.mat-form-field-invalid.mat-focused .mat-mdc-text-field-wrapper .mat-mdc-form-field-icon-suffix {
  color: var(--mat-form-field-error-focus-trailing-icon-color, var(--mat-sys-error));
}

.mat-mdc-form-field-icon-prefix,
[dir=rtl] .mat-mdc-form-field-icon-suffix {
  padding: 0 4px 0 0;
}

.mat-mdc-form-field-icon-suffix,
[dir=rtl] .mat-mdc-form-field-icon-prefix {
  padding: 0 0 0 4px;
}

.mat-mdc-form-field-subscript-wrapper .mat-icon,
.mat-mdc-form-field label .mat-icon {
  width: 1em;
  height: 1em;
  font-size: inherit;
}

.mat-mdc-form-field-infix {
  flex: auto;
  min-width: 0;
  width: 180px;
  position: relative;
  box-sizing: border-box;
}
.mat-mdc-form-field-infix:has(textarea[cols]) {
  width: auto;
}

.mat-mdc-form-field .mdc-notched-outline__notch {
  margin-left: -1px;
  -webkit-clip-path: inset(-9em -999em -9em 1px);
  clip-path: inset(-9em -999em -9em 1px);
}
[dir=rtl] .mat-mdc-form-field .mdc-notched-outline__notch {
  margin-left: 0;
  margin-right: -1px;
  -webkit-clip-path: inset(-9em 1px -9em -999em);
  clip-path: inset(-9em 1px -9em -999em);
}

.mat-mdc-form-field.mat-form-field-animations-enabled .mdc-floating-label {
  transition: transform 150ms cubic-bezier(0.4, 0, 0.2, 1), color 150ms cubic-bezier(0.4, 0, 0.2, 1);
}
.mat-mdc-form-field.mat-form-field-animations-enabled .mdc-text-field__input {
  transition: opacity 150ms cubic-bezier(0.4, 0, 0.2, 1);
}
.mat-mdc-form-field.mat-form-field-animations-enabled .mdc-text-field__input::placeholder {
  transition: opacity 67ms cubic-bezier(0.4, 0, 0.2, 1);
}
.mat-mdc-form-field.mat-form-field-animations-enabled .mdc-text-field__input::-moz-placeholder {
  transition: opacity 67ms cubic-bezier(0.4, 0, 0.2, 1);
}
.mat-mdc-form-field.mat-form-field-animations-enabled .mdc-text-field__input::-webkit-input-placeholder {
  transition: opacity 67ms cubic-bezier(0.4, 0, 0.2, 1);
}
.mat-mdc-form-field.mat-form-field-animations-enabled .mdc-text-field__input:-ms-input-placeholder {
  transition: opacity 67ms cubic-bezier(0.4, 0, 0.2, 1);
}
.mat-mdc-form-field.mat-form-field-animations-enabled.mdc-text-field--no-label .mdc-text-field__input::placeholder, .mat-mdc-form-field.mat-form-field-animations-enabled.mdc-text-field--focused .mdc-text-field__input::placeholder {
  transition-delay: 40ms;
  transition-duration: 110ms;
}
.mat-mdc-form-field.mat-form-field-animations-enabled.mdc-text-field--no-label .mdc-text-field__input::-moz-placeholder, .mat-mdc-form-field.mat-form-field-animations-enabled.mdc-text-field--focused .mdc-text-field__input::-moz-placeholder {
  transition-delay: 40ms;
  transition-duration: 110ms;
}
.mat-mdc-form-field.mat-form-field-animations-enabled.mdc-text-field--no-label .mdc-text-field__input::-webkit-input-placeholder, .mat-mdc-form-field.mat-form-field-animations-enabled.mdc-text-field--focused .mdc-text-field__input::-webkit-input-placeholder {
  transition-delay: 40ms;
  transition-duration: 110ms;
}
.mat-mdc-form-field.mat-form-field-animations-enabled.mdc-text-field--no-label .mdc-text-field__input:-ms-input-placeholder, .mat-mdc-form-field.mat-form-field-animations-enabled.mdc-text-field--focused .mdc-text-field__input:-ms-input-placeholder {
  transition-delay: 40ms;
  transition-duration: 110ms;
}
.mat-mdc-form-field.mat-form-field-animations-enabled .mdc-text-field--filled:not(.mdc-ripple-upgraded):focus .mdc-text-field__ripple::before {
  transition-duration: 75ms;
}
.mat-mdc-form-field.mat-form-field-animations-enabled .mdc-line-ripple::after {
  transition: transform 180ms cubic-bezier(0.4, 0, 0.2, 1), opacity 180ms cubic-bezier(0.4, 0, 0.2, 1);
}
.mat-mdc-form-field.mat-form-field-animations-enabled .mat-mdc-form-field-hint-wrapper,
.mat-mdc-form-field.mat-form-field-animations-enabled .mat-mdc-form-field-error-wrapper {
  animation-duration: 300ms;
}

.mdc-notched-outline .mdc-floating-label {
  max-width: calc(100% + 1px);
}

.mdc-notched-outline--upgraded .mdc-floating-label--float-above {
  max-width: calc(133.3333333333% + 1px);
}
`],encapsulation:2})}return i})();var vt=(()=>{class i{static \u0275fac=function(n){return new(n||i)};static \u0275mod=CE({type:i});static \u0275inj=cu({imports:[Pn$1,Wi,jo]})}return i})();var br=(()=>{class i{static \u0275fac=function(n){return new(n||i)};static \u0275cmp=wE({type:i,selectors:[["ng-component"]],hostAttrs:["cdk-text-field-style-loader",""],decls:0,vars:0,template:function(n,r){},styles:[`textarea.cdk-textarea-autosize {
  resize: none;
}

textarea.cdk-textarea-autosize-measuring {
  padding: 2px 0 !important;
  box-sizing: content-box !important;
  height: auto !important;
  overflow: hidden !important;
}

textarea.cdk-textarea-autosize-measuring-firefox {
  padding: 2px 0 !important;
  box-sizing: content-box !important;
  height: 0 !important;
}

@keyframes cdk-text-field-autofill-start { /*!*/ }
@keyframes cdk-text-field-autofill-end { /*!*/ }
.cdk-text-field-autofill-monitored:-webkit-autofill {
  animation: cdk-text-field-autofill-start 0s 1ms;
}

.cdk-text-field-autofill-monitored:not(:-webkit-autofill) {
  animation: cdk-text-field-autofill-end 0s 1ms;
}
`],encapsulation:2})}return i})(),yr={passive:true},$i=(()=>{class i{_platform=T(_);_ngZone=T(Ne$1);_renderer=T(gr$1).createRenderer(null,null);_styleLoader=T(L$1);_monitoredElements=new Map;monitor(e){if(!this._platform.isBrowser)return Et;this._styleLoader.load(br);let n=S(e),r=this._monitoredElements.get(n);if(r)return r.subject;let o=new ne$1,a="cdk-text-field-autofilled",m=$=>{$.animationName==="cdk-text-field-autofill-start"&&!n.classList.contains(a)?(n.classList.add(a),this._ngZone.run(()=>o.next({target:$.target,isAutofilled:true}))):$.animationName==="cdk-text-field-autofill-end"&&n.classList.contains(a)&&(n.classList.remove(a),this._ngZone.run(()=>o.next({target:$.target,isAutofilled:false})));},B=this._ngZone.runOutsideAngular(()=>(n.classList.add("cdk-text-field-autofill-monitored"),this._renderer.listen(n,"animationstart",m,yr)));return this._monitoredElements.set(n,{subject:o,unlisten:B}),o}stopMonitoring(e){let n=S(e),r=this._monitoredElements.get(n);r&&(r.unlisten(),r.subject.complete(),n.classList.remove("cdk-text-field-autofill-monitored"),n.classList.remove("cdk-text-field-autofilled"),this._monitoredElements.delete(n));}ngOnDestroy(){this._monitoredElements.forEach((e,n)=>this.stopMonitoring(n));}static \u0275fac=function(n){return new(n||i)};static \u0275prov=Er({token:i,factory:i.\u0275fac})}return i})();var Zi=(()=>{class i{static \u0275fac=function(n){return new(n||i)};static \u0275mod=CE({type:i});static \u0275inj=cu({})}return i})();var Qi=new x("MAT_INPUT_VALUE_ACCESSOR");var xr=["button","checkbox","file","hidden","image","radio","range","reset","submit"],Cr=new x("MAT_INPUT_CONFIG"),aa=(()=>{class i{_elementRef=T(Dr);_platform=T(_);ngControl=T(P,{optional:true,self:true});_autofillMonitor=T($i);_ngZone=T(Ne$1);_formField=T(_t,{optional:true});_renderer=T(_I);_uid=T(ye).getId("mat-input-");_previousNativeValue;_inputValueAccessor;_signalBasedValueAccessor;_previousPlaceholder=null;_errorStateTracker;_config=T(Cr,{optional:true});_cleanupIosKeyup;_cleanupWebkitWheel;_isServer=false;_isNativeSelect=false;_isTextarea=false;_isInFormField=false;focused=false;stateChanges=new ne$1;controlType="mat-input";autofilled=false;get disabled(){return this._disabled}set disabled(e){this._disabled=Us(e),this.focused&&(this.focused=false,this.stateChanges.next());}_disabled=false;get id(){return this._id}set id(e){this._id=e||this._uid;}_id;placeholder;name;get required(){return this._required??this.ngControl?.control?.hasValidator(pe.required)??false}set required(e){this._required=Us(e);}_required;get type(){return this._type}set type(e){this._type=e||"text",this._validateType(),!this._isTextarea&&Ts().has(this._type)&&(this._elementRef.nativeElement.type=this._type);}_type="text";get errorStateMatcher(){return this._errorStateTracker.matcher}set errorStateMatcher(e){this._errorStateTracker.matcher=e;}userAriaDescribedBy;get value(){return this._signalBasedValueAccessor?this._signalBasedValueAccessor.value():this._inputValueAccessor.value}set value(e){e!==this.value&&(this._signalBasedValueAccessor?this._signalBasedValueAccessor.value.set(e):this._inputValueAccessor.value=e,this.stateChanges.next());}get readonly(){return this._readonly}set readonly(e){this._readonly=Us(e);}_readonly=false;disabledInteractive;get errorState(){return this._errorStateTracker.errorState}set errorState(e){this._errorStateTracker.errorState=e;}_neverEmptyInputTypes=["date","datetime","datetime-local","month","time","week"].filter(e=>Ts().has(e));constructor(){let e=T(ct,{optional:true}),n=T(ft,{optional:true}),r=T(d),o=T(Qi,{optional:true,self:true}),a=this._elementRef.nativeElement,m=a.nodeName.toLowerCase();o?$o(o.value)?this._signalBasedValueAccessor=o:this._inputValueAccessor=o:this._inputValueAccessor=a,this._previousNativeValue=this.value,this.id=this.id,this._platform.IOS&&this._ngZone.runOutsideAngular(()=>{this._cleanupIosKeyup=this._renderer.listen(a,"keyup",this._iOSKeyupListener);}),this._errorStateTracker=new s(r,this.ngControl,n,e,this.stateChanges),this._isServer=!this._platform.isBrowser,this._isNativeSelect=m==="select",this._isTextarea=m==="textarea",this._isInFormField=!!this._formField,this.disabledInteractive=this._config?.disabledInteractive||false,this._isNativeSelect&&(this.controlType=a.multiple?"mat-native-select-multiple":"mat-native-select"),this._signalBasedValueAccessor&&nd(()=>{this._signalBasedValueAccessor.value(),this.stateChanges.next();});}ngAfterViewInit(){this._platform.isBrowser&&this._autofillMonitor.monitor(this._elementRef.nativeElement).subscribe(e=>{this.autofilled=e.isAutofilled,this.stateChanges.next();});}ngOnChanges(){this.stateChanges.next();}ngOnDestroy(){this.stateChanges.complete(),this._platform.isBrowser&&this._autofillMonitor.stopMonitoring(this._elementRef.nativeElement),this._cleanupIosKeyup?.(),this._cleanupWebkitWheel?.();}ngDoCheck(){this.ngControl&&(this.updateErrorState(),this.ngControl.disabled!==null&&this.ngControl.disabled!==this.disabled&&(this.disabled=this.ngControl.disabled,this.stateChanges.next())),this._dirtyCheckNativeValue(),this._dirtyCheckPlaceholder();}focus(e){this._elementRef.nativeElement.focus(e);}updateErrorState(){this._errorStateTracker.updateErrorState();}_focusChanged(e){if(e!==this.focused){if(!this._isNativeSelect&&e&&this.disabled&&this.disabledInteractive){let n=this._elementRef.nativeElement;n.type==="number"?(n.type="text",n.setSelectionRange(0,0),n.type="number"):n.setSelectionRange(0,0);}this.focused=e,this.stateChanges.next();}}_onInput(){}_dirtyCheckNativeValue(){let e=this._elementRef.nativeElement.value;this._previousNativeValue!==e&&(this._previousNativeValue=e,this.stateChanges.next());}_dirtyCheckPlaceholder(){let e=this._getPlaceholder();if(e!==this._previousPlaceholder){let n=this._elementRef.nativeElement;this._previousPlaceholder=e,e?n.setAttribute("placeholder",e):n.removeAttribute("placeholder");}}_getPlaceholder(){return this.placeholder||null}_validateType(){xr.indexOf(this._type)>-1;}_isNeverEmpty(){return this._neverEmptyInputTypes.indexOf(this._type)>-1}_isBadInput(){let e=this._elementRef.nativeElement.validity;return e&&e.badInput}get empty(){return !this._isNeverEmpty()&&!this._elementRef.nativeElement.value&&!this._isBadInput()&&!this.autofilled}get shouldLabelFloat(){if(this._isNativeSelect){let e=this._elementRef.nativeElement,n=e.options[0];return this.focused||e.multiple||!this.empty||!!(e.selectedIndex>-1&&n&&n.label)}else return this.focused&&!this.disabled||!this.empty}get describedByIds(){return this._elementRef.nativeElement.getAttribute("aria-describedby")?.split(" ")||[]}setDescribedByIds(e){let n=this._elementRef.nativeElement;e.length?n.setAttribute("aria-describedby",e.join(" ")):n.removeAttribute("aria-describedby");}onContainerClick(){this.focused||this.focus();}_isInlineSelect(){let e=this._elementRef.nativeElement;return this._isNativeSelect&&(e.multiple||e.size>1)}_iOSKeyupListener=e=>{let n=e.target;!n.value&&n.selectionStart===0&&n.selectionEnd===0&&(n.setSelectionRange(1,1),n.setSelectionRange(0,0));};_getReadonlyAttribute(){return this._isNativeSelect?null:this.readonly||this.disabled&&this.disabledInteractive?"true":null}static \u0275fac=function(n){return new(n||i)};static \u0275dir=ME({type:i,selectors:[["input","matInput",""],["textarea","matInput",""],["select","matNativeControl",""],["input","matNativeControl",""],["textarea","matNativeControl",""]],hostAttrs:[1,"mat-mdc-input-element"],hostVars:21,hostBindings:function(n,r){n&1&&nh("focus",function(){return r._focusChanged(true)})("blur",function(){return r._focusChanged(false)})("input",function(){return r._onInput()}),n&2&&(eh("id",r.id)("disabled",r.disabled&&!r.disabledInteractive)("required",r.required),Qp("name",r.name||null)("readonly",r._getReadonlyAttribute())("aria-disabled",r.disabled&&r.disabledInteractive?"true":null)("aria-invalid",r.empty&&r.required?null:r.errorState)("aria-required",r.required)("id",r.id),fh("mat-input-server",r._isServer)("mat-mdc-form-field-textarea-control",r._isInFormField&&r._isTextarea)("mat-mdc-form-field-input-control",r._isInFormField)("mat-mdc-input-disabled-interactive",r.disabledInteractive)("mdc-text-field__input",r._isInFormField)("mat-mdc-native-select-inline",r._isInlineSelect()));},inputs:{disabled:"disabled",id:"id",placeholder:"placeholder",name:"name",required:"required",type:"type",errorStateMatcher:"errorStateMatcher",userAriaDescribedBy:[0,"aria-describedby","userAriaDescribedBy"],value:"value",readonly:"readonly",disabledInteractive:[2,"disabledInteractive","disabledInteractive",V0]},exportAs:["matInput"],features:[JD([{provide:gt,useExisting:i}]),Gm]})}return i})(),sa=(()=>{class i{static \u0275fac=function(n){return new(n||i)};static \u0275mod=CE({type:i});static \u0275inj=cu({imports:[vt,vt,Zi,jo]})}return i})();export{$r as $,Ai as A,Cn as C,Ei as E,Gr as G,Ln as L,Pe as P,Ri as R,Ve as V,Wr as W,Zr as Z,_n as _,jn as a,Wi as b,aa as c,hr as d,fr as e,ft as f,bn as g,ht as h,ii as i,jr as j,kn as k,pt as l,L as m,ne as n,_t as o,pe as p,qr as q,P as r,sa as s,ct as t,gt as u,vt as v,C as w};