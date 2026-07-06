import {W,p as q,bS as yr$1,ar as N,bf as cE,bg as Jl,aU as dE,bi as Cr$1,ao as lI,an as vr$1,ax as Sp,aD as PD,q as qp,X as Xp,ap as He,bT as zm,b3 as pe,ay as Sm,bU as hE,T,aJ as YF,bp as _,as as ye$1,am as _e,n as zF,bb as ZD,bV as QF,bN as Hs,j as jo,aq as ee,ak as nt$1,bt as Er$1,o as Gu,at as Eg,ac as we$1,bW as yg,e as Gt$1,bM as Dg,aP as ug,bX as e0,s as sE,bY as jn$1,aA as XE,S as xp,h as hi$1,A as AE,aB as eD,k as kc,_ as _v,R as RE,Z as Zp,aE as zp,r as oD,aF as nD,aG as rD,bZ as Qp,aH as Gp,aK as j,b_ as ei$1,b$ as Co,c0 as tc,aw as gt,az as JF,aT as XF,P as PE,F as Fp,c1 as Pp,L as LE,aC as Lp,b7 as mD,ai as Jp,aj as Ni,aL as F,aM as oo,av as E,br as Ap,aY as dr$1,aI as Pt$1,aN as gh,b4 as nv,a as jp,c2 as YD,c3 as lg,c4 as pe$1,B as qe,aX as Vp,aR as Oc,aS as Pc,bG as $p,K as KE,a_ as iD,c5 as hr,c6 as ot$1,c7 as b,c8 as xc,c9 as De$1,bj as yo,M as MD,c as sh,a9 as $E,aa as Tu,ab as Cu,bu as st$1,b9 as Bh,D as vl,ca as _e$1,aO as It$1,b8 as El,by as M,cb as Ig,aQ as Kn$1,cc as Pn$1,cd as _$1,ce as vg,cf as _t,ag as OE,cg as WD}from'./main-GKG6RN24.js';var Rn=(()=>{class i{_renderer;_elementRef;onChange=e=>{};onTouched=()=>{};constructor(e,t){this._renderer=e,this._elementRef=t;}setProperty(e,t){this._renderer.setProperty(this._elementRef.nativeElement,e,t);}registerOnTouched(e){this.onTouched=e;}registerOnChange(e){this.onChange=e;}setDisabledState(e){this.setProperty("disabled",e);}static \u0275fac=function(t){return new(t||i)(Cr$1(lI),Cr$1(vr$1))};static \u0275dir=dE({type:i})}return i})(),Pn=(()=>{class i extends Rn{static \u0275fac=(()=>{let e;return function(a){return (e||(e=zm(i)))(a||i)}})();static \u0275dir=dE({type:i,features:[Sp]})}return i})(),De=new N("");var Li={provide:De,useExisting:yo(()=>On),multi:true};function Bi(){let i=pe$1()?pe$1().getUserAgent():"";return /android (\d+)/.test(i.toLowerCase())}var zi=new N(""),On=(()=>{class i extends Rn{_compositionMode;_composing=false;constructor(e,t,a){super(e,t),this._compositionMode=a,this._compositionMode==null&&(this._compositionMode=!Bi());}writeValue(e){let t=e??"";this.setProperty("value",t);}_handleInput(e){(!this._compositionMode||this._compositionMode&&!this._composing)&&this.onChange(e);}_compositionStart(){this._composing=true;}_compositionEnd(e){this._composing=false,this._compositionMode&&this.onChange(e);}static \u0275fac=function(t){return new(t||i)(Cr$1(lI),Cr$1(vr$1),Cr$1(zi,8))};static \u0275dir=dE({type:i,selectors:[["input","formControlName","",3,"type","checkbox",3,"ngNoCva",""],["textarea","formControlName","",3,"ngNoCva",""],["input","formControl","",3,"type","checkbox",3,"ngNoCva",""],["textarea","formControl","",3,"ngNoCva",""],["input","ngModel","",3,"type","checkbox",3,"ngNoCva",""],["textarea","ngModel","",3,"ngNoCva",""],["","ngDefaultControl",""]],hostBindings:function(t,a){t&1&&qp("input",function(o){return a._handleInput(o.target.value)})("blur",function(){return a.onTouched()})("compositionstart",function(){return a._compositionStart()})("compositionend",function(o){return a._compositionEnd(o.target.value)});},standalone:false,features:[PD([Li]),Sp]})}return i})();function Et(i){return i==null||Ft(i)===0}function Ft(i){return i==null?null:Array.isArray(i)||typeof i=="string"?i.length:i instanceof Set?i.size:null}var Z=new N(""),we=new N(""),ji=/^(?=.{1,254}$)(?=.{1,64}@)[a-zA-Z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&'*+/=?^_`{|}~-]+)*@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/,Ct=class{static min(n){return Nn(n)}static max(n){return Ln(n)}static required(n){return Bn(n)}static requiredTrue(n){return Hi(n)}static email(n){return Gi(n)}static minLength(n){return qi(n)}static maxLength(n){return zn(n)}static pattern(n){return Wi(n)}static nullValidator(n){return Ze()}static compose(n){return Un(n)}static composeAsync(n){return $n(n)}};function Nn(i){return n=>{if(n.value==null||i==null)return null;let e=parseFloat(n.value);return !isNaN(e)&&e<i?{min:{min:i,actual:n.value}}:null}}function Ln(i){return n=>{if(n.value==null||i==null)return null;let e=parseFloat(n.value);return !isNaN(e)&&e>i?{max:{max:i,actual:n.value}}:null}}function Bn(i){return Et(i.value)?{required:true}:null}function Hi(i){return i.value===true?null:{required:true}}function Gi(i){return Et(i.value)||ji.test(i.value)?null:{email:true}}function qi(i){return n=>{let e=n.value?.length??Ft(n.value);return e===null||e===0?null:e<i?{minlength:{requiredLength:i,actualLength:e}}:null}}function zn(i){return n=>{let e=n.value?.length??Ft(n.value);return e!==null&&e>i?{maxlength:{requiredLength:i,actualLength:e}}:null}}function Wi(i){if(!i)return Ze;let n,e;return typeof i=="string"?(e="",i.charAt(0)!=="^"&&(e+="^"),e+=i,i.charAt(i.length-1)!=="$"&&(e+="$"),n=new RegExp(e)):(e=i.toString(),n=i),t=>{if(Et(t.value))return null;let a=t.value;return n.test(a)?null:{pattern:{requiredPattern:e,actualValue:a}}}}function Ze(i){return null}function jn(i){return i!=null}function Hn(i){return xc(i)?De$1(i):i}function Gn(i){let n={};return i.forEach(e=>{n=e!=null?q(q({},n),e):n;}),Object.keys(n).length===0?null:n}function qn(i,n){return n.map(e=>e(i))}function Ui(i){return !i.validate}function Wn(i){return i.map(n=>Ui(n)?n:e=>n.validate(e))}function Un(i){if(!i)return null;let n=i.filter(jn);return n.length==0?null:function(e){return Gn(qn(e,n))}}function Tt(i){return i!=null?Un(Wn(i)):null}function $n(i){if(!i)return null;let n=i.filter(jn);return n.length==0?null:function(e){let t=qn(e,n).map(Hn);return lg(t).pipe(we$1(Gn))}}function At(i){return i!=null?$n(Wn(i)):null}function Fn(i,n){return i===null?[n]:Array.isArray(i)?[...i,n]:[i,n]}function Qn(i){return i._rawValidators}function Zn(i){return i._rawAsyncValidators}function Dt(i){return i?Array.isArray(i)?i:[i]:[]}function Ye(i,n){return Array.isArray(i)?i.includes(n):i===n}function Tn(i,n){let e=Dt(n);return Dt(i).forEach(a=>{Ye(e,a)||e.push(a);}),e}function An(i,n){return Dt(n).filter(e=>!Ye(i,e))}var Xe=class{get value(){return this.control?this.control.value:null}get valid(){return this.control?this.control.valid:null}get invalid(){return this.control?this.control.invalid:null}get pending(){return this.control?this.control.pending:null}get disabled(){return this.control?this.control.disabled:null}get enabled(){return this.control?this.control.enabled:null}get errors(){return this.control?this.control.errors:null}get pristine(){return this.control?this.control.pristine:null}get dirty(){return this.control?this.control.dirty:null}get touched(){return this.control?this.control.touched:null}get status(){return this.control?this.control.status:null}get untouched(){return this.control?this.control.untouched:null}get statusChanges(){return this.control?this.control.statusChanges:null}get valueChanges(){return this.control?this.control.valueChanges:null}get path(){return null}_composedValidatorFn;_composedAsyncValidatorFn;_rawValidators=[];_rawAsyncValidators=[];_setValidators(n){this._rawValidators=n||[],this._composedValidatorFn=Tt(this._rawValidators);}_setAsyncValidators(n){this._rawAsyncValidators=n||[],this._composedAsyncValidatorFn=At(this._rawAsyncValidators);}get validator(){return this._composedValidatorFn||null}get asyncValidator(){return this._composedAsyncValidatorFn||null}_onDestroyCallbacks=[];_registerOnDestroy(n){this._onDestroyCallbacks.push(n);}_invokeOnDestroyCallbacks(){this._onDestroyCallbacks.forEach(n=>n()),this._onDestroyCallbacks=[];}reset(n=void 0){this.control?.reset(n);}hasError(n,e){return this.control?this.control.hasError(n,e):false}getError(n,e){return this.control?this.control.getError(n,e):null}},P=class extends Xe{name;get formDirective(){return null}get path(){return null}};var Ae="VALID",$e="INVALID",ve="PENDING",Ie="DISABLED",ie=class{},Ke=class extends ie{value;source;constructor(n,e){super(),this.value=n,this.source=e;}},Ve=class extends ie{pristine;source;constructor(n,e){super(),this.pristine=n,this.source=e;}},Re=class extends ie{touched;source;constructor(n,e){super(),this.touched=n,this.source=e;}},ye=class extends ie{status;source;constructor(n,e){super(),this.status=n,this.source=e;}},Je=class extends ie{source;constructor(n){super(),this.source=n;}},de=class extends ie{source;constructor(n){super(),this.source=n;}};function It(i){return (at(i)?i.validators:i)||null}function $i(i){return Array.isArray(i)?Tt(i):i||null}function St(i,n){return (at(n)?n.asyncValidators:i)||null}function Qi(i){return Array.isArray(i)?At(i):i||null}function at(i){return i!=null&&!Array.isArray(i)&&typeof i=="object"}function Yn(i,n,e){let t=i.controls;if(!(n?Object.keys(t):t).length)throw new b(1e3,"");if(!Kn(t,e))throw new b(1001,"")}function Xn(i,n,e){i._forEachChild((t,a)=>{if(e[a]===void 0)throw new b(-1002,"")});}var xe=class{_pendingDirty=false;_hasOwnPendingAsyncValidator=null;_pendingTouched=false;_onCollectionChange=()=>{};_updateOn;_hasRequired=jo(false);_parent=null;_asyncValidationSubscription;_composedValidatorFn;_composedAsyncValidatorFn;_rawValidators;_rawAsyncValidators;value;constructor(n,e){this._assignValidators(n),this._assignAsyncValidators(e);}get validator(){return this._composedValidatorFn}set validator(n){this._rawValidators=this._composedValidatorFn=n,this._updateHasRequiredValidator();}get asyncValidator(){return this._composedAsyncValidatorFn}set asyncValidator(n){this._rawAsyncValidators=this._composedAsyncValidatorFn=n;}get parent(){return this._parent}get status(){return YD(this.statusReactive)}set status(n){YD(()=>this.statusReactive.set(n));}_status=ZD(()=>this.statusReactive());statusReactive=jo(void 0);get valid(){return this.status===Ae}get invalid(){return this.status===$e}get pending(){return this.status===ve}get disabled(){return this.status===Ie}get enabled(){return this.status!==Ie}errors;get pristine(){return YD(this.pristineReactive)}set pristine(n){YD(()=>this.pristineReactive.set(n));}_pristine=ZD(()=>this.pristineReactive());pristineReactive=jo(true);get dirty(){return !this.pristine}get touched(){return YD(this.touchedReactive)}set touched(n){YD(()=>this.touchedReactive.set(n));}_touched=ZD(()=>this.touchedReactive());touchedReactive=jo(false);get untouched(){return !this.touched}_events=new ee;events=this._events.asObservable();valueChanges;statusChanges;get updateOn(){return this._updateOn?this._updateOn:this.parent?this.parent.updateOn:"change"}setValidators(n){this._assignValidators(n);}setAsyncValidators(n){this._assignAsyncValidators(n);}addValidators(n){this.setValidators(Tn(n,this._rawValidators));}addAsyncValidators(n){this.setAsyncValidators(Tn(n,this._rawAsyncValidators));}removeValidators(n){this.setValidators(An(n,this._rawValidators));}removeAsyncValidators(n){this.setAsyncValidators(An(n,this._rawAsyncValidators));}hasValidator(n){return Ye(this._rawValidators,n)}hasAsyncValidator(n){return Ye(this._rawAsyncValidators,n)}clearValidators(){this.validator=null;}clearAsyncValidators(){this.asyncValidator=null;}markAsTouched(n={}){let e=this.touched===false;this.touched=true;let t=n.sourceControl??this;n.onlySelf||this._parent?.markAsTouched(W(q({},n),{sourceControl:t})),e&&n.emitEvent!==false&&this._events.next(new Re(true,t));}markAllAsDirty(n={}){this.markAsDirty({onlySelf:true,emitEvent:n.emitEvent,sourceControl:this}),this._forEachChild(e=>e.markAllAsDirty(n));}markAllAsTouched(n={}){this.markAsTouched({onlySelf:true,emitEvent:n.emitEvent,sourceControl:this}),this._forEachChild(e=>e.markAllAsTouched(n));}markAsUntouched(n={}){let e=this.touched===true;this.touched=false,this._pendingTouched=false;let t=n.sourceControl??this;this._forEachChild(a=>{a.markAsUntouched({onlySelf:true,emitEvent:n.emitEvent,sourceControl:t});}),n.onlySelf||this._parent?._updateTouched(n,t),e&&n.emitEvent!==false&&this._events.next(new Re(false,t));}markAsDirty(n={}){let e=this.pristine===true;this.pristine=false;let t=n.sourceControl??this;n.onlySelf||this._parent?.markAsDirty(W(q({},n),{sourceControl:t})),e&&n.emitEvent!==false&&this._events.next(new Ve(false,t));}markAsPristine(n={}){let e=this.pristine===false;this.pristine=true,this._pendingDirty=false;let t=n.sourceControl??this;this._forEachChild(a=>{a.markAsPristine({onlySelf:true,emitEvent:n.emitEvent});}),n.onlySelf||this._parent?._updatePristine(n,t),e&&n.emitEvent!==false&&this._events.next(new Ve(true,t));}markAsPending(n={}){this.status=ve;let e=n.sourceControl??this;n.emitEvent!==false&&(this._events.next(new ye(this.status,e)),this.statusChanges.emit(this.status)),n.onlySelf||this._parent?.markAsPending(W(q({},n),{sourceControl:e}));}disable(n={}){let e=this._parentMarkedDirty(n.onlySelf);this.status=Ie,this.errors=null,this._forEachChild(a=>{a.disable(W(q({},n),{onlySelf:true}));}),this._updateValue();let t=n.sourceControl??this;n.emitEvent!==false&&(this._events.next(new Ke(this.value,t)),this._events.next(new ye(this.status,t)),this.valueChanges.emit(this.value),this.statusChanges.emit(this.status)),this._updateAncestors(W(q({},n),{skipPristineCheck:e}),this),this._onDisabledChange.forEach(a=>a(true));}enable(n={}){let e=this._parentMarkedDirty(n.onlySelf);this.status=Ae,this._forEachChild(t=>{t.enable(W(q({},n),{onlySelf:true}));}),this.updateValueAndValidity({onlySelf:true,emitEvent:n.emitEvent}),this._updateAncestors(W(q({},n),{skipPristineCheck:e}),this),this._onDisabledChange.forEach(t=>t(false));}_updateAncestors(n,e){n.onlySelf||(this._parent?.updateValueAndValidity(n),n.skipPristineCheck||this._parent?._updatePristine({},e),this._parent?._updateTouched({},e));}setParent(n){this._parent=n;}getRawValue(){return this.value}updateValueAndValidity(n={}){if(this._setInitialStatus(),this._updateValue(),this.enabled){let t=this._cancelExistingSubscription();this.errors=this._runValidator(),this.status=this._calculateStatus(),(this.status===Ae||this.status===ve)&&this._runAsyncValidator(t,n.emitEvent);}let e=n.sourceControl??this;n.emitEvent!==false&&(this._events.next(new Ke(this.value,e)),this._events.next(new ye(this.status,e)),this.valueChanges.emit(this.value),this.statusChanges.emit(this.status)),n.onlySelf||this._parent?.updateValueAndValidity(W(q({},n),{sourceControl:e}));}_updateTreeValidity(n={emitEvent:true}){this._forEachChild(e=>e._updateTreeValidity(n)),this.updateValueAndValidity({onlySelf:true,emitEvent:n.emitEvent});}_setInitialStatus(){this.status=this._allControlsDisabled()?Ie:Ae;}_runValidator(){return this.validator?this.validator(this):null}_runAsyncValidator(n,e){if(this.asyncValidator){this.status=ve,this._hasOwnPendingAsyncValidator={emitEvent:e!==false,shouldHaveEmitted:n!==false};let t=Hn(this.asyncValidator(this));this._asyncValidationSubscription=t.subscribe(a=>{this._hasOwnPendingAsyncValidator=null,this.setErrors(a,{emitEvent:e,shouldHaveEmitted:n});});}}_cancelExistingSubscription(){if(this._asyncValidationSubscription){this._asyncValidationSubscription.unsubscribe();let n=(this._hasOwnPendingAsyncValidator?.emitEvent||this._hasOwnPendingAsyncValidator?.shouldHaveEmitted)??false;return this._hasOwnPendingAsyncValidator=null,n}return  false}setErrors(n,e={}){this.errors=n,this._updateControlsErrors(e.emitEvent!==false,this,e.shouldHaveEmitted);}get(n){let e=n;return e==null||(Array.isArray(e)||(e=e.split(".")),e.length===0)?null:e.reduce((t,a)=>t&&t._find(a),this)}getError(n,e){let t=e?this.get(e):this;return t?.errors?t.errors[n]:null}hasError(n,e){return !!this.getError(n,e)}get root(){let n=this;for(;n._parent;)n=n._parent;return n}_updateControlsErrors(n,e,t){this.status=this._calculateStatus(),n&&this.statusChanges.emit(this.status),(n||t)&&this._events.next(new ye(this.status,e)),this._parent&&this._parent._updateControlsErrors(n,e,t);}_initObservables(){this.valueChanges=new He,this.statusChanges=new He;}_calculateStatus(){return this._allControlsDisabled()?Ie:this.errors?$e:this._hasOwnPendingAsyncValidator||this._anyControlsHaveStatus(ve)?ve:this._anyControlsHaveStatus($e)?$e:Ae}_anyControlsHaveStatus(n){return this._anyControls(e=>e.status===n)}_anyControlsDirty(){return this._anyControls(n=>n.dirty)}_anyControlsTouched(){return this._anyControls(n=>n.touched)}_updatePristine(n,e){let t=!this._anyControlsDirty(),a=this.pristine!==t;this.pristine=t,n.onlySelf||this._parent?._updatePristine(n,e),a&&this._events.next(new Ve(this.pristine,e));}_updateTouched(n={},e){this.touched=this._anyControlsTouched(),this._events.next(new Re(this.touched,e)),n.onlySelf||this._parent?._updateTouched(n,e);}_onDisabledChange=[];_registerOnCollectionChange(n){this._onCollectionChange=n;}_setUpdateStrategy(n){at(n)&&n.updateOn!=null&&(this._updateOn=n.updateOn);}_parentMarkedDirty(n){return !n&&!!this._parent?.dirty&&!this._parent._anyControlsDirty()}_find(n){return null}_assignValidators(n){this._rawValidators=Array.isArray(n)?n.slice():n,this._composedValidatorFn=$i(this._rawValidators),this._updateHasRequiredValidator();}_assignAsyncValidators(n){this._rawAsyncValidators=Array.isArray(n)?n.slice():n,this._composedAsyncValidatorFn=Qi(this._rawAsyncValidators);}_updateHasRequiredValidator(){YD(()=>this._hasRequired.set(this.hasValidator(Ct.required)));}};function Kn(i,n){return Object.hasOwn(i,n)}function Zi(i){return i.tagName==="INPUT"||i.tagName==="SELECT"||i.tagName==="TEXTAREA"}function Yi(i,n,e,t){switch(e){case "name":i.setAttribute(n,e,t);break;case "disabled":case "readonly":case "required":t?i.setAttribute(n,e,""):i.removeAttribute(n,e);break;case "max":case "min":case "minLength":case "maxLength":t!==void 0?i.setAttribute(n,e,t.toString()):i.removeAttribute(n,e);break}}var wt=class{kind;context;control;message;constructor({kind:n,context:e,control:t}){this.kind=n,this.context=e,this.control=t;}};function Xi(i){return typeof i=="number"?i:parseInt(i,10)}function Jn(i){return typeof i=="number"?i:parseFloat(i)}var rt=(()=>{class i{_validator=Ze;_onChange;_enabled;ngOnChanges(e){if(this.inputName in e){let t=this.normalizeInput(e[this.inputName].currentValue);this._enabled=this.enabled(t),this._validator=this._enabled?this.createValidator(t):Ze,this._onChange?.();}}validate(e){return this._validator(e)}registerOnValidatorChange(e){this._onChange=e;}enabled(e){return e!=null}static \u0275fac=function(t){return new(t||i)};static \u0275dir=dE({type:i,features:[Sm]})}return i})(),Ki={provide:Z,useExisting:yo(()=>Ji),multi:true},Ji=(()=>{class i extends rt{max;inputName="max";normalizeInput=e=>Jn(e);createValidator=e=>Ln(e);static \u0275fac=(()=>{let e;return function(a){return (e||(e=zm(i)))(a||i)}})();static \u0275dir=dE({type:i,selectors:[["input","type","number","max","","formControlName",""],["input","type","number","max","","formControl",""],["input","type","number","max","","ngModel",""]],hostVars:1,hostBindings:function(t,a){t&2&&Lp("max",a._enabled?a.max:null);},inputs:{max:"max"},standalone:false,features:[PD([Ki]),Sp]})}return i})(),ea={provide:Z,useExisting:yo(()=>ta),multi:true},ta=(()=>{class i extends rt{min;inputName="min";normalizeInput=e=>Jn(e);createValidator=e=>Nn(e);static \u0275fac=(()=>{let e;return function(a){return (e||(e=zm(i)))(a||i)}})();static \u0275dir=dE({type:i,selectors:[["input","type","number","min","","formControlName",""],["input","type","number","min","","formControl",""],["input","type","number","min","","ngModel",""]],hostVars:1,hostBindings:function(t,a){t&2&&Lp("min",a._enabled?a.min:null);},inputs:{min:"min"},standalone:false,features:[PD([ea]),Sp]})}return i})(),na={provide:Z,useExisting:yo(()=>ei),multi:true};var ei=(()=>{class i extends rt{required;inputName="required";normalizeInput=JF;createValidator=e=>Bn;enabled(e){return e}static \u0275fac=(()=>{let e;return function(a){return (e||(e=zm(i)))(a||i)}})();static \u0275dir=dE({type:i,selectors:[["","required","","formControlName","",3,"type","checkbox"],["","required","","formControl","",3,"type","checkbox"],["","required","","ngModel","",3,"type","checkbox"]],hostVars:1,hostBindings:function(t,a){t&2&&Lp("required",a._enabled?"":null);},inputs:{required:"required"},standalone:false,features:[PD([na]),Sp]})}return i})();var ia={provide:Z,useExisting:yo(()=>aa),multi:true},aa=(()=>{class i extends rt{maxlength;inputName="maxlength";normalizeInput=e=>Xi(e);createValidator=e=>zn(e);static \u0275fac=(()=>{let e;return function(a){return (e||(e=zm(i)))(a||i)}})();static \u0275dir=dE({type:i,selectors:[["","maxlength","","formControlName",""],["","maxlength","","formControl",""],["","maxlength","","ngModel",""]],hostVars:1,hostBindings:function(t,a){t&2&&Lp("maxlength",a._enabled?a.maxlength:null);},inputs:{maxlength:"maxlength"},standalone:false,features:[PD([ia]),Sp]})}return i})();var ra=new N(""),Pe=new N("",{factory:()=>ot}),ot="always";function Vt(i,n){return [...n.path,i]}function ti(i,n,e=ot){Rt(i,n),n.valueAccessor.writeValue(i.value),(i.disabled||e==="always")&&n.valueAccessor.setDisabledState?.(i.disabled),sa(i,n),da(i,n),la(i,n),oa(i,n);}function et(i,n,e=true){let t=()=>{};n?.valueAccessor?.registerOnChange(t),n?.valueAccessor?.registerOnTouched(t),nt(i,n),i&&(n._invokeOnDestroyCallbacks(),i._registerOnCollectionChange(()=>{}));}function tt(i,n){i.forEach(e=>{e.registerOnValidatorChange&&e.registerOnValidatorChange(n);});}function oa(i,n){if(n.valueAccessor.setDisabledState){let e=t=>{n.valueAccessor.setDisabledState(t);};i.registerOnDisabledChange(e),n._registerOnDestroy(()=>{i._unregisterOnDisabledChange(e);});}}function Rt(i,n){let e=Qn(i);n.validator!==null?i.setValidators(Fn(e,n.validator)):typeof e=="function"&&i.setValidators([e]);let t=Zn(i);n.asyncValidator!==null?i.setAsyncValidators(Fn(t,n.asyncValidator)):typeof t=="function"&&i.setAsyncValidators([t]);let a=()=>i.updateValueAndValidity();tt(n._rawValidators,a),tt(n._rawAsyncValidators,a);}function nt(i,n){let e=false;if(i!==null){if(n.validator!==null){let a=Qn(i);if(Array.isArray(a)&&a.length>0){let r=a.filter(o=>o!==n.validator);r.length!==a.length&&(e=true,i.setValidators(r));}}if(n.asyncValidator!==null){let a=Zn(i);if(Array.isArray(a)&&a.length>0){let r=a.filter(o=>o!==n.asyncValidator);r.length!==a.length&&(e=true,i.setAsyncValidators(r));}}}let t=()=>{};return tt(n._rawValidators,t),tt(n._rawAsyncValidators,t),e}function sa(i,n){n.valueAccessor.registerOnChange(e=>{i._pendingValue=e,i._pendingChange=true,i._pendingDirty=true,i.updateOn==="change"&&ni(i,n);});}function la(i,n){n.valueAccessor.registerOnTouched(()=>{i._pendingTouched=true,i.updateOn==="blur"&&i._pendingChange&&ni(i,n),i.updateOn!=="submit"&&i.markAsTouched();});}function ni(i,n){i._pendingDirty&&i.markAsDirty(),i.setValue(i._pendingValue,{emitModelToViewChange:false}),n.viewToModelUpdate(i._pendingValue),i._pendingChange=false;}function da(i,n){let e=(t,a)=>{n.valueAccessor.writeValue(t),a&&n.viewToModelUpdate(t);};i.registerOnChange(e),n._registerOnDestroy(()=>{i._unregisterOnChange(e);});}function ii(i,n){Rt(i,n);}function ca(i,n){return nt(i,n)}function ai(i,n){if(!i.hasOwnProperty("model"))return  false;let e=i.model;return e.isFirstChange()?true:!Object.is(n,e.currentValue)}function ma(i){return Object.getPrototypeOf(i.constructor)===Pn}function ri(i,n){i._syncPendingControls(),n.forEach(e=>{let t=e.control;t.updateOn==="submit"&&t._pendingChange&&(e.viewToModelUpdate(t._pendingValue),t._pendingChange=false);});}function ua(i,n){if(!n)return null;let e,t,a;return n.forEach(r=>{r.constructor===On?e=r:ma(r)?t=r:a=r;}),a||t||e||null}function fa(i,n){let e=i.indexOf(n);e>-1&&i.splice(e,1);}var oi={provide:ra,useFactory:()=>{let i=T(ae,{self:true});return {setParseErrors:n=>{i.setParseErrorSource(n);},set onReset(n){i.onReset=n;}}}},ae=class extends Xe{_parent=null;name=null;valueAccessor=null;isCustomControlBased=false;userOnReset;resetSubscription;set onReset(n){this.userOnReset=n,this.resetSubscription?.unsubscribe(),this.resetSubscription=void 0,this.control&&(this.resetSubscription=this.control.events.subscribe(e=>{e instanceof de&&this.control&&this.userOnReset?.(this.control.value);}),this.subscription?.add(this.resetSubscription));}isNativeFormElement=false;rawValueAccessors;_selectedValueAccessor=null;get selectedValueAccessor(){return this._selectedValueAccessor??=ua(this,this.rawValueAccessors)}parseErrorsValidator=null;renderer;injector;requiredValidatorViaDi;subscription;customControlBindings=null;constructor(n,e,t){super(),this.injector=n,this.renderer=e,this.rawValueAccessors=t,this.injector?.get(qe)?.onDestroy(()=>{this.removeParseErrorsValidator(this.control),this.subscription?.unsubscribe();});}setupCustomControl(){this.subscription?.unsubscribe();let n=this.injector?.get(YF);if(!this.control||!n)return;let e=n.markForCheck.bind(n);this.subscription=new j,this.subscription.add(this.control.valueChanges.subscribe(e)),this.subscription.add(this.control.statusChanges.subscribe(e)),this.resetSubscription?.unsubscribe(),this.resetSubscription=void 0,this.userOnReset&&(this.resetSubscription=this.control.events.subscribe(t=>{t instanceof de&&this.control&&this.userOnReset?.(this.control.value);}),this.subscription.add(this.resetSubscription)),this.parseErrorsValidator&&this.control.addValidators(this.parseErrorsValidator);}ngControlCreate(n){!n.nativeElement.hasAttribute?.("ngNoCva")&&(this.rawValueAccessors&&this.rawValueAccessors.length>0||this.valueAccessor!==null)||!n.customControl||(this.isCustomControlBased=true,n.listenToCustomControlModel(a=>{this.control?.setValue(a,{emitModelToViewChange:false}),this.control?.markAsDirty(),this.viewToModelUpdate(a);}),n.listenToCustomControlOutput("touch",()=>{this.control?.markAsTouched();}),this.customControlBindings={},this.isNativeFormElement=Zi(n.nativeElement),this.requiredValidatorViaDi=this._rawValidators.find(a=>a instanceof ei));}ngControlUpdate(n,e){if(!this.isCustomControlBased)return;let t=this.control,a=this.customControlBindings;Object.is(a.value,t.value)||(a.value=t.value,n.setCustomControlModelInput(t.value)),this.bindControlProperty(n,a,"touched",t.touched),this.bindControlProperty(n,a,"dirty",t.dirty),this.bindControlProperty(n,a,"valid",t.valid),this.bindControlProperty(n,a,"invalid",t.invalid),this.bindControlProperty(n,a,"pending",t.pending),this.bindControlProperty(n,a,"disabled",t.disabled),this.shouldBindRequired&&this.bindControlProperty(n,a,"required",this.isRequired);let r=t.errors;if(a.errors!==r){a.errors=r;let o=this._convertErrors(r);n.setInputOnDirectives("errors",o);}}get isRequired(){return (this.requiredValidatorViaDi?._enabled||this.control?._hasRequired())??false}get shouldBindRequired(){return  true}bindControlProperty(n,e,t,a){if(e[t]===a)return;e[t]=a;let r=n.setInputOnDirectives(t,a);this.isNativeFormElement&&!r&&(t==="disabled"||t==="required")&&this.renderer&&Yi(this.renderer,n.nativeElement,t,a);}_convertErrors(n){if(n===null)return [];let e=this.control;return Object.entries(n).map(([t,a])=>new wt({context:a,kind:t,control:e}))}setParseErrorSource(n){if(n===void 0)return;let e=null,t=ZD(()=>{let a=n();return a.length===0?null:a.reduce((r,o)=>(r[o.kind]=o,r),{})});this.parseErrorsValidator=(()=>e).bind(this),Gu(()=>{e=t(),this.control?.updateValueAndValidity({emitEvent:false});},{injector:this.injector});}removeParseErrorsValidator(n){this.parseErrorsValidator&&(n?.removeValidators(this.parseErrorsValidator),n?.updateValueAndValidity({emitEvent:false}));}},it=class{_cd;constructor(n){this._cd=n;}get isTouched(){return this._cd?.control?._touched?.(),!!this._cd?.control?.touched}get isUntouched(){return !!this._cd?.control?.untouched}get isPristine(){return this._cd?.control?._pristine?.(),!!this._cd?.control?.pristine}get isDirty(){return !!this._cd?.control?.dirty}get isValid(){return this._cd?.control?._status?.(),!!this._cd?.control?.valid}get isInvalid(){return !!this._cd?.control?.invalid}get isPending(){return !!this._cd?.control?.pending}get isSubmitted(){return this._cd?._submitted?.(),!!this._cd?.submitted}};var so=(()=>{class i extends it{constructor(e){super(e);}static \u0275fac=function(t){return new(t||i)(Cr$1(ae,2))};static \u0275dir=dE({type:i,selectors:[["","formControlName",""],["","ngModel",""],["","formControl",""]],hostVars:14,hostBindings:function(t,a){t&2&&Xp("ng-untouched",a.isUntouched)("ng-touched",a.isTouched)("ng-pristine",a.isPristine)("ng-dirty",a.isDirty)("ng-valid",a.isValid)("ng-invalid",a.isInvalid)("ng-pending",a.isPending);},standalone:false,features:[Sp]})}return i})(),lo=(()=>{class i extends it{constructor(e){super(e);}static \u0275fac=function(t){return new(t||i)(Cr$1(P,10))};static \u0275dir=dE({type:i,selectors:[["","formGroupName",""],["","formArrayName",""],["","ngModelGroup",""],["","formGroup",""],["","formArray",""],["form",3,"ngNoForm",""],["","ngForm",""]],hostVars:16,hostBindings:function(t,a){t&2&&Xp("ng-untouched",a.isUntouched)("ng-touched",a.isTouched)("ng-pristine",a.isPristine)("ng-dirty",a.isDirty)("ng-valid",a.isValid)("ng-invalid",a.isInvalid)("ng-pending",a.isPending)("ng-submitted",a.isSubmitted);},standalone:false,features:[Sp]})}return i})(),Ce=class extends xe{constructor(n,e,t){super(It(e),St(t,e)),this.controls=n,this._initObservables(),this._setUpdateStrategy(e),this._setUpControls(),this.updateValueAndValidity({onlySelf:true,emitEvent:!!this.asyncValidator});}controls;registerControl(n,e){let t=this._find(n);return t||(this.controls[n]=e,e.setParent(this),e._registerOnCollectionChange(this._onCollectionChange),e)}addControl(n,e,t={}){this.registerControl(n,e),this.updateValueAndValidity({emitEvent:t.emitEvent}),this._onCollectionChange();}removeControl(n,e={}){let t=this._find(n);t&&t._registerOnCollectionChange(()=>{}),delete this.controls[n],this.updateValueAndValidity({emitEvent:e.emitEvent}),this._onCollectionChange();}setControl(n,e,t={}){let a=this._find(n);a&&a._registerOnCollectionChange(()=>{}),delete this.controls[n],e&&this.registerControl(n,e),this.updateValueAndValidity({emitEvent:t.emitEvent}),this._onCollectionChange();}contains(n){return this._find(n)?.enabled===true}setValue(n,e={}){YD(()=>{Xn(this,true,n),Object.keys(n).forEach(t=>{Yn(this,true,t),this.controls[t].setValue(n[t],{onlySelf:true,emitEvent:e.emitEvent});}),this.updateValueAndValidity(e);});}patchValue(n,e={}){n!=null&&(Object.keys(n).forEach(t=>{let a=this._find(t);a&&a.patchValue(n[t],{onlySelf:true,emitEvent:e.emitEvent});}),this.updateValueAndValidity(e));}reset(n={},e={}){this._forEachChild((t,a)=>{t.reset(n?n[a]:null,W(q({},e),{onlySelf:true}));}),this._updatePristine(e,this),this._updateTouched(e,this),this.updateValueAndValidity(e),e?.emitEvent!==false&&this._events.next(new de(this));}getRawValue(){return this._reduceChildren({},(n,e,t)=>(n[t]=e.getRawValue(),n))}_syncPendingControls(){let n=this._reduceChildren(false,(e,t)=>t._syncPendingControls()?true:e);return n&&this.updateValueAndValidity({onlySelf:true}),n}_forEachChild(n){Object.keys(this.controls).forEach(e=>{let t=this.controls[e];t&&n(t,e);});}_setUpControls(){this._forEachChild(n=>{n.setParent(this),n._registerOnCollectionChange(this._onCollectionChange);});}_updateValue(){this.value=this._reduceValue();}_anyControls(n){for(let[e,t]of Object.entries(this.controls))if(this.contains(e)&&n(t))return  true;return  false}_reduceValue(){let n={};return this._reduceChildren(n,(e,t,a)=>((t.enabled||this.disabled)&&(e[a]=t.value),e))}_reduceChildren(n,e){let t=n;return this._forEachChild((a,r)=>{t=e(t,a,r);}),t}_allControlsDisabled(){for(let n of Object.keys(this.controls))if(this.controls[n].enabled)return  false;return Object.keys(this.controls).length>0||this.disabled}_find(n){return Kn(this.controls,n)?this.controls[n]:null}};var kt=class extends Ce{};var ha={provide:P,useExisting:yo(()=>pa)},Se=Promise.resolve(),pa=(()=>{class i extends P{callSetDisabledState;get submitted(){return YD(this.submittedReactive)}_submitted=ZD(()=>this.submittedReactive());submittedReactive=jo(false);_directives=new Set;form;ngSubmit=new He;options;constructor(e,t,a){super(),this.callSetDisabledState=a,this.form=new Ce({},Tt(e),At(t));}ngAfterViewInit(){this._setUpdateStrategy();}get formDirective(){return this}get control(){return this.form}get path(){return []}get controls(){return this.form.controls}addControl(e){Se.then(()=>{let t=this._findContainer(e.path);e.control=t.registerControl(e.name,e.control),e._setupWithForm(this.callSetDisabledState),e.control.updateValueAndValidity({emitEvent:false}),this._directives.add(e);});}getControl(e){return this.form.get(e.path)}removeControl(e){Se.then(()=>{this._findContainer(e.path)?.removeControl(e.name),this._directives.delete(e);});}addFormGroup(e){Se.then(()=>{let t=this._findContainer(e.path),a=new Ce({});ii(a,e),t.registerControl(e.name,a),a.updateValueAndValidity({emitEvent:false});});}removeFormGroup(e){Se.then(()=>{this._findContainer(e.path)?.removeControl?.(e.name);});}getFormGroup(e){return this.form.get(e.path)}updateModel(e,t){Se.then(()=>{this.form.get(e.path).setValue(t);});}setValue(e){this.control.setValue(e);}onSubmit(e){return this.submittedReactive.set(true),ri(this.form,this._directives),this.ngSubmit.emit(e),this.form._events.next(new Je(this.control)),e?.target?.method==="dialog"}onReset(){this.resetForm();}resetForm(e=void 0){this.form.reset(e),this.submittedReactive.set(false);}_setUpdateStrategy(){this.options&&this.options.updateOn!=null&&(this.form._updateOn=this.options.updateOn);}_findContainer(e){return e.pop(),e.length?this.form.get(e):this.form}static \u0275fac=function(t){return new(t||i)(Cr$1(Z,10),Cr$1(we,10),Cr$1(Pe,8))};static \u0275dir=dE({type:i,selectors:[["form",3,"ngNoForm","",3,"formGroup","",3,"formArray",""],["ng-form"],["","ngForm",""]],hostBindings:function(t,a){t&1&&qp("submit",function(o){return a.onSubmit(o)})("reset",function(){return a.onReset()});},inputs:{options:[0,"ngFormOptions","options"]},outputs:{ngSubmit:"ngSubmit"},exportAs:["ngForm"],standalone:false,features:[PD([ha]),Sp]})}return i})();function In(i,n){let e=i.indexOf(n);e>-1&&i.splice(e,1);}function Sn(i){return typeof i=="object"&&i!==null&&Object.keys(i).length===2&&"value"in i&&"disabled"in i}var Qe=class extends xe{defaultValue=null;_onChange=[];_pendingValue;_pendingChange=false;constructor(n=null,e,t){super(It(e),St(t,e)),this._applyFormState(n),this._setUpdateStrategy(e),this._initObservables(),this.updateValueAndValidity({onlySelf:true,emitEvent:!!this.asyncValidator}),at(e)&&(e.nonNullable||e.initialValueIsDefault)&&(Sn(n)?this.defaultValue=n.value:this.defaultValue=n);}setValue(n,e={}){YD(()=>{this.value=this._pendingValue=n,this._onChange.length&&e.emitModelToViewChange!==false&&this._onChange.forEach(t=>t(this.value,e.emitViewToModelChange!==false)),this.updateValueAndValidity(e);});}patchValue(n,e={}){this.setValue(n,e);}reset(n=this.defaultValue,e={}){this._applyFormState(n),this.markAsPristine(e),this.markAsUntouched(e),this.setValue(this.value,e),e.overwriteDefaultValue&&(this.defaultValue=this.value),this._pendingChange=false,e?.emitEvent!==false&&this._events.next(new de(this));}_updateValue(){}_anyControls(n){return  false}_allControlsDisabled(){return this.disabled}registerOnChange(n){this._onChange.push(n);}_unregisterOnChange(n){In(this._onChange,n);}registerOnDisabledChange(n){this._onDisabledChange.push(n);}_unregisterOnDisabledChange(n){In(this._onDisabledChange,n);}_forEachChild(n){}_syncPendingControls(){return this.updateOn==="submit"&&(this._pendingDirty&&this.markAsDirty(),this._pendingTouched&&this.markAsTouched(),this._pendingChange)?(this.setValue(this._pendingValue,{onlySelf:true,emitModelToViewChange:false}),true):false}_applyFormState(n){Sn(n)?(this.value=this._pendingValue=n.value,n.disabled?this.disable({onlySelf:true,emitEvent:false}):this.enable({onlySelf:true,emitEvent:false})):this.value=this._pendingValue=n;}};var ba=i=>i instanceof Qe,_a=(()=>{class i extends P{_parent;ngOnInit(){this._checkParentType(),this.formDirective.addFormGroup(this);}ngOnDestroy(){this.formDirective?.removeFormGroup(this);}get control(){return this.formDirective.getFormGroup(this)}get path(){return Vt(this.name==null?this.name:this.name.toString(),this._parent)}get formDirective(){return this._parent?this._parent.formDirective:null}_checkParentType(){}static \u0275fac=(()=>{let e;return function(a){return (e||(e=zm(i)))(a||i)}})();static \u0275dir=dE({type:i,standalone:false,features:[Sp]})}return i})();var mo=(()=>{class i{static \u0275fac=function(t){return new(t||i)};static \u0275dir=dE({type:i,selectors:[["form",3,"ngNoForm","",3,"ngNativeValidate",""]],hostAttrs:["novalidate",""],standalone:false})}return i})(),ga={provide:De,useExisting:yo(()=>va),multi:true},va=(()=>{class i extends Pn{writeValue(e){let t=e??"";this.setProperty("value",t);}registerOnChange(e){this.onChange=t=>{e(t==""?null:parseFloat(t));};}static \u0275fac=(()=>{let e;return function(a){return (e||(e=zm(i)))(a||i)}})();static \u0275dir=dE({type:i,selectors:[["input","type","number","formControlName","",3,"ngNoCva",""],["input","type","number","formControl","",3,"ngNoCva",""],["input","type","number","ngModel","",3,"ngNoCva",""]],hostBindings:function(t,a){t&1&&qp("input",function(o){return a.onChange(o.target.value)})("blur",function(){return a.onTouched()});},standalone:false,features:[PD([ga]),Sp]})}return i})();var Mt=class extends xe{constructor(n,e,t){super(It(e),St(t,e)),this.controls=n,this._initObservables(),this._setUpdateStrategy(e),this._setUpControls(),this.updateValueAndValidity({onlySelf:true,emitEvent:!!this.asyncValidator});}controls;at(n){return this.controls[this._adjustIndex(n)]}push(n,e={}){Array.isArray(n)?n.forEach(t=>{this.controls.push(t),this._registerControl(t);}):(this.controls.push(n),this._registerControl(n)),this.updateValueAndValidity({emitEvent:e.emitEvent}),this._onCollectionChange();}insert(n,e,t={}){this.controls.splice(n,0,e),this._registerControl(e),this.updateValueAndValidity({emitEvent:t.emitEvent});}removeAt(n,e={}){let t=this._adjustIndex(n);t<0&&(t=0),this.controls[t]&&this.controls[t]._registerOnCollectionChange(()=>{}),this.controls.splice(t,1),this.updateValueAndValidity({emitEvent:e.emitEvent});}setControl(n,e,t={}){let a=this._adjustIndex(n);a<0&&(a=0),this.controls[a]&&this.controls[a]._registerOnCollectionChange(()=>{}),this.controls.splice(a,1),e&&(this.controls.splice(a,0,e),this._registerControl(e)),this.updateValueAndValidity({emitEvent:t.emitEvent}),this._onCollectionChange();}get length(){return this.controls.length}setValue(n,e={}){YD(()=>{Xn(this,false,n),n.forEach((t,a)=>{Yn(this,false,a),this.at(a).setValue(t,{onlySelf:true,emitEvent:e.emitEvent});}),this.updateValueAndValidity(e);});}patchValue(n,e={}){n!=null&&(n.forEach((t,a)=>{this.at(a)&&this.at(a).patchValue(t,{onlySelf:true,emitEvent:e.emitEvent});}),this.updateValueAndValidity(e));}reset(n=[],e={}){this._forEachChild((t,a)=>{t.reset(n[a],W(q({},e),{onlySelf:true}));}),this._updatePristine(e,this),this._updateTouched(e,this),this.updateValueAndValidity(e),e?.emitEvent!==false&&this._events.next(new de(this));}getRawValue(){return this.controls.map(n=>n.getRawValue())}clear(n={}){this.controls.length<1||(this._forEachChild(e=>e._registerOnCollectionChange(()=>{})),this.controls.splice(0),this.updateValueAndValidity({emitEvent:n.emitEvent}));}_adjustIndex(n){return n<0?n+this.length:n}_syncPendingControls(){let n=this.controls.reduce((e,t)=>t._syncPendingControls()?true:e,false);return n&&this.updateValueAndValidity({onlySelf:true}),n}_forEachChild(n){this.controls.forEach((e,t)=>{n(e,t);});}_updateValue(){this.value=this.controls.filter(n=>n.enabled||this.disabled).map(n=>n.value);}_anyControls(n){return this.controls.some(e=>e.enabled&&n(e))}_setUpControls(){this._forEachChild(n=>this._registerControl(n));}_allControlsDisabled(){for(let n of this.controls)if(n.enabled)return  false;return this.controls.length>0||this.disabled}_registerControl(n){n.setParent(this),n._registerOnCollectionChange(this._onCollectionChange);}_find(n){return this.at(n)??null}};var si=(()=>{class i extends P{callSetDisabledState;get submitted(){return YD(this._submittedReactive)}set submitted(e){this._submittedReactive.set(e);}_submitted=ZD(()=>this._submittedReactive());_submittedReactive=jo(false);_oldForm;_onCollectionChange=()=>this._updateDomValue();directives=[];constructor(e,t,a){super(),this.callSetDisabledState=a,this._setValidators(e),this._setAsyncValidators(t);}ngOnChanges(e){this.onChanges(e);}ngOnDestroy(){this.onDestroy();}onChanges(e){this._checkFormPresent(),e.hasOwnProperty("form")&&(this._updateValidators(),this._updateDomValue(),this._updateRegistrations(),this._oldForm=this.form);}onDestroy(){this.form&&(nt(this.form,this),this.form._onCollectionChange===this._onCollectionChange&&this.form._registerOnCollectionChange(()=>{}));}get formDirective(){return this}get path(){return []}addControl(e){let t=this.form.get(e.path);return e._setupWithForm(t,this.callSetDisabledState),t.updateValueAndValidity({emitEvent:false}),this.directives.push(e),t}getControl(e){return this.form.get(e.path)}removeControl(e){et(e.control||null,e,false),fa(this.directives,e);}addFormGroup(e){this._setUpFormContainer(e);}removeFormGroup(e){this._cleanUpFormContainer(e);}getFormGroup(e){return this.form.get(e.path)}getFormArray(e){return this.form.get(e.path)}addFormArray(e){this._setUpFormContainer(e);}removeFormArray(e){this._cleanUpFormContainer(e);}updateModel(e,t){this.form.get(e.path).setValue(t);}onReset(){this.resetForm();}resetForm(e=void 0,t={}){this.form.reset(e,t),this._submittedReactive.set(false);}onSubmit(e){return this.submitted=true,ri(this.form,this.directives),this.ngSubmit.emit(e),this.form._events.next(new Je(this.control)),e?.target?.method==="dialog"}_updateDomValue(){this.directives.forEach(e=>{let t=e.control,a=this.form.get(e.path);t!==a&&(et(t||null,e),ba(a)&&e._setupWithForm(a,this.callSetDisabledState));}),this.form._updateTreeValidity({emitEvent:false});}_setUpFormContainer(e){let t=this.form.get(e.path);ii(t,e),t.updateValueAndValidity({emitEvent:false});}_cleanUpFormContainer(e){let t=this.form?.get(e.path);t&&ca(t,e)&&t.updateValueAndValidity({emitEvent:false});}_updateRegistrations(){this.form._registerOnCollectionChange(this._onCollectionChange),this._oldForm?._registerOnCollectionChange(()=>{});}_updateValidators(){Rt(this.form,this),this._oldForm&&nt(this._oldForm,this);}_checkFormPresent(){this.form;}static \u0275fac=function(t){return new(t||i)(Cr$1(Z,10),Cr$1(we,10),Cr$1(Pe,8))};static \u0275dir=dE({type:i,features:[Sp,Sm]})}return i})();var Pt=new N(""),ya={provide:ae,useExisting:yo(()=>xa)},xa=(()=>{class i extends ae{_ngModelWarningConfig;callSetDisabledState;viewModel;form;set isDisabled(e){}model;update=new He;static _ngModelWarningSentOnce=false;_ngModelWarningSent=false;constructor(e,t,a,r,o,p,C){super(C,p,a),this._ngModelWarningConfig=r,this.callSetDisabledState=o,this._setValidators(e),this._setAsyncValidators(t);}ngOnChanges(e){if(this._isControlChanged(e)){let t=e.form.previousValue;t&&(et(t,this,false),this.removeParseErrorsValidator(t)),this.isCustomControlBased?this.setupCustomControl():(this.valueAccessor??=this.selectedValueAccessor,ti(this.form,this,this.callSetDisabledState)),this.form.updateValueAndValidity({emitEvent:false});}ai(e,this.viewModel)&&(this.form.setValue(this.model),this.viewModel=this.model);}ngOnDestroy(){this.form&&et(this.form,this,false);}get path(){return []}get control(){return this.form}viewToModelUpdate(e){this.viewModel=e,this.update.emit(e);}_isControlChanged(e){return e.hasOwnProperty("form")}\u0275ngControlCreate(e){super.ngControlCreate(e);}\u0275ngControlUpdate(e){super.ngControlUpdate(e,true);}static \u0275fac=function(t){return new(t||i)(Cr$1(Z,10),Cr$1(we,10),Cr$1(De,10),Cr$1(Pt,8),Cr$1(Pe,8),Cr$1(lI,8),Cr$1(pe,8))};static \u0275dir=dE({type:i,selectors:[["","formControl",""]],inputs:{form:[0,"formControl","form"],isDisabled:[0,"disabled","isDisabled"],model:[0,"ngModel","model"]},outputs:{update:"ngModelChange"},exportAs:["ngForm"],standalone:false,features:[PD([ya,oi]),Sp,Sm,hE(null)]})}return i})(),Ca={provide:P,useExisting:yo(()=>li)},li=(()=>{class i extends _a{name=null;constructor(e,t,a){super(),this._parent=e,this._setValidators(t),this._setAsyncValidators(a);}_checkParentType(){ci(this._parent);}static \u0275fac=function(t){return new(t||i)(Cr$1(P,13),Cr$1(Z,10),Cr$1(we,10))};static \u0275dir=dE({type:i,selectors:[["","formGroupName",""]],inputs:{name:[0,"formGroupName","name"]},standalone:false,features:[PD([Ca]),Sp]})}return i})(),Da={provide:P,useExisting:yo(()=>di)},di=(()=>{class i extends P{_parent;name=null;constructor(e,t,a){super(),this._parent=e,this._setValidators(t),this._setAsyncValidators(a);}ngOnInit(){ci(this._parent),this.formDirective.addFormArray(this);}ngOnDestroy(){this.formDirective?.removeFormArray(this);}get control(){return this.formDirective.getFormArray(this)}get formDirective(){return this._parent?this._parent.formDirective:null}get path(){return Vt(this.name==null?this.name:this.name.toString(),this._parent)}static \u0275fac=function(t){return new(t||i)(Cr$1(P,13),Cr$1(Z,10),Cr$1(we,10))};static \u0275dir=dE({type:i,selectors:[["","formArrayName",""]],inputs:{name:[0,"formArrayName","name"]},standalone:false,features:[PD([Da]),Sp]})}return i})();function ci(i){return !(i instanceof li)&&!(i instanceof si)&&!(i instanceof di)}var wa={provide:ae,useExisting:yo(()=>ka)},ka=(()=>{class i extends ae{_ngModelWarningConfig;_added=false;viewModel;control;name=null;set isDisabled(e){}model;update=new He;static _ngModelWarningSentOnce=false;_ngModelWarningSent=false;constructor(e,t,a,r,o,p,C){super(C,p,r),this._ngModelWarningConfig=o,this._parent=e,this._setValidators(t),this._setAsyncValidators(a);}_setupWithForm(e,t){this.control=e,this.isCustomControlBased?this.setupCustomControl():(this.valueAccessor??=this.selectedValueAccessor,ti(e,this,t));}ngOnChanges(e){this._added||this._setUpControl(),ai(e,this.viewModel)&&(this.viewModel=this.model,this.formDirective.updateModel(this,this.model));}ngOnDestroy(){this.formDirective?.removeControl(this);}viewToModelUpdate(e){this.viewModel=e,this.update.emit(e);}get path(){return Vt(this.name==null?this.name:this.name.toString(),this._parent)}get formDirective(){return this._parent?this._parent.formDirective:null}_setUpControl(){this.control=this.formDirective.addControl(this),this._added=true;}\u0275ngControlCreate(e){super.ngControlCreate(e);}\u0275ngControlUpdate(e){this.isCustomControlBased&&(this._added||this._setUpControl(),super.ngControlUpdate(e,true));}static \u0275fac=function(t){return new(t||i)(Cr$1(P,13),Cr$1(Z,10),Cr$1(we,10),Cr$1(De,10),Cr$1(Pt,8),Cr$1(lI,8),Cr$1(pe,8))};static \u0275dir=dE({type:i,selectors:[["","formControlName",""]],inputs:{name:[0,"formControlName","name"],isDisabled:[0,"disabled","isDisabled"],model:[0,"ngModel","model"]},outputs:{update:"ngModelChange"},standalone:false,features:[PD([wa,oi]),Sp,Sm,hE(null)]})}return i})();var Ma={provide:P,useExisting:yo(()=>Ea)},Ea=(()=>{class i extends si{form=null;ngSubmit=new He;get control(){return this.form}static \u0275fac=(()=>{let e;return function(a){return (e||(e=zm(i)))(a||i)}})();static \u0275dir=dE({type:i,selectors:[["","formGroup",""]],hostBindings:function(t,a){t&1&&qp("submit",function(o){return a.onSubmit(o)})("reset",function(){return a.onReset()});},inputs:{form:[0,"formGroup","form"]},outputs:{ngSubmit:"ngSubmit"},exportAs:["ngForm"],standalone:false,features:[PD([Ma]),Sp]})}return i})();var mi=(()=>{class i{static \u0275fac=function(t){return new(t||i)};static \u0275mod=cE({type:i});static \u0275inj=Jl({})}return i})();function Vn(i){return !!i&&(i.asyncValidators!==void 0||i.validators!==void 0||i.updateOn!==void 0)}var uo=(()=>{class i{useNonNullable=false;get nonNullable(){let e=new i;return e.useNonNullable=true,e}group(e,t=null){let a=this._reduceControls(e),r={};return Vn(t)?r=t:t!==null&&(r.validators=t.validator,r.asyncValidators=t.asyncValidator),new Ce(a,r)}record(e,t=null){let a=this._reduceControls(e);return new kt(a,t)}control(e,t,a){let r={};return this.useNonNullable?(Vn(t)?r=t:(r.validators=t,r.asyncValidators=a),new Qe(e,W(q({},r),{nonNullable:true}))):new Qe(e,t,a)}array(e,t,a){let r=e.map(o=>this._createControl(o));return new Mt(r,t,a)}_reduceControls(e){let t={};return Object.keys(e).forEach(a=>{t[a]=this._createControl(e[a]);}),t}_createControl(e){if(e instanceof Qe)return e;if(e instanceof xe)return e;if(Array.isArray(e)){let t=e[0],a=e.length>1?e[1]:null,r=e.length>2?e[2]:null;return this.control(t,a,r)}else return this.control(e)}static \u0275fac=function(t){return new(t||i)};static \u0275prov=yr$1({token:i,factory:i.\u0275fac})}return i})();var fo=(()=>{class i{static withConfig(e){return {ngModule:i,providers:[{provide:Pe,useValue:e.callSetDisabledState??ot}]}}static \u0275fac=function(t){return new(t||i)};static \u0275mod=cE({type:i});static \u0275inj=Jl({imports:[mi]})}return i})(),ho=(()=>{class i{static withConfig(e){return {ngModule:i,providers:[{provide:Pt,useValue:e.warnOnNgModelWithFormControl??"always"},{provide:Pe,useValue:e.callSetDisabledState??ot}]}}static \u0275fac=function(t){return new(t||i)};static \u0275mod=cE({type:i});static \u0275inj=Jl({imports:[mi]})}return i})();var Ot=class{_box;_destroyed=new ee;_resizeSubject=new ee;_resizeObserver;_elementObservables=new Map;constructor(n){this._box=n,typeof ResizeObserver<"u"&&(this._resizeObserver=new ResizeObserver(e=>this._resizeSubject.next(e)));}observe(n){return this._elementObservables.has(n)||this._elementObservables.set(n,new M(e=>{let t=this._resizeSubject.subscribe(e);return this._resizeObserver?.observe(n,{box:this._box}),()=>{this._resizeObserver?.unobserve(n),t.unsubscribe(),this._elementObservables.delete(n);}}).pipe(Gt$1(e=>e.some(t=>t.target===n)),vg({bufferSize:1,refCount:true}),Dg(this._destroyed))),this._elementObservables.get(n)}destroy(){this._destroyed.next(),this._destroyed.complete(),this._resizeSubject.complete(),this._elementObservables.clear();}},st=(()=>{class i{_cleanupErrorListener;_observers=new Map;_ngZone=T(_e);constructor(){}ngOnDestroy(){for(let[,e]of this._observers)e.destroy();this._observers.clear(),this._cleanupErrorListener?.();}observe(e,t){let a=t?.box||"content-box";return this._observers.has(a)||this._observers.set(a,new Ot(a)),this._observers.get(a).observe(e)}static \u0275fac=function(t){return new(t||i)};static \u0275prov=yr$1({token:i,factory:i.\u0275fac})}return i})();var Fa=["notch"],Ta=["*"],ui=["iconPrefixContainer"],fi=["textPrefixContainer"],hi=["iconSuffixContainer"],pi=["textSuffixContainer"],Aa=["textField"],Ia=["*",[["mat-label"]],[["","matPrefix",""],["","matIconPrefix",""]],[["","matTextPrefix",""]],[["","matTextSuffix",""]],[["","matSuffix",""],["","matIconSuffix",""]],[["mat-error"],["","matError",""]],[["mat-hint",3,"align","end"]],[["mat-hint","align","end"]]],Sa=["*","mat-label","[matPrefix], [matIconPrefix]","[matTextPrefix]","[matTextSuffix]","[matSuffix], [matIconSuffix]","mat-error, [matError]","mat-hint:not([align='end'])","mat-hint[align='end']"];function Va(i,n){i&1&&jp(0,"span",21);}function Ra(i,n){if(i&1&&(hi$1(0,"label",20),eD(1,1),AE(2,Va,1,0,"span",21),kc()),i&2){let e=KE(2);Fp("floating",e._shouldLabelFloat())("monitorResize",e._hasOutline())("id",e._labelId),Lp("for",e._control.disableAutomaticLabeling?null:e._control.id),_v(2),RE(!e.hideRequiredMarker&&e._control.required?2:-1);}}function Pa(i,n){if(i&1&&AE(0,Ra,3,5,"label",20),i&2){let e=KE();RE(e._hasFloatingLabel()?0:-1);}}function Oa(i,n){i&1&&jp(0,"div",7);}function Na(i,n){}function La(i,n){if(i&1&&xp(0,Na,0,0,"ng-template",13),i&2){KE(2);let e=iD(1);Fp("ngTemplateOutlet",e);}}function Ba(i,n){if(i&1&&(hi$1(0,"div",9),AE(1,La,1,1,null,13),kc()),i&2){let e=KE();Fp("matFormFieldNotchedOutlineOpen",e._shouldLabelFloat()),_v(),RE(e._forceDisplayInfixLabel()?-1:1);}}function za(i,n){i&1&&(hi$1(0,"div",10,2),eD(2,2),kc());}function ja(i,n){i&1&&(hi$1(0,"div",11,3),eD(2,3),kc());}function Ha(i,n){}function Ga(i,n){if(i&1&&xp(0,Ha,0,0,"ng-template",13),i&2){KE();let e=iD(1);Fp("ngTemplateOutlet",e);}}function qa(i,n){i&1&&(hi$1(0,"div",14,4),eD(2,4),kc());}function Wa(i,n){i&1&&(hi$1(0,"div",15,5),eD(2,5),kc());}function Ua(i,n){i&1&&jp(0,"div",16);}function $a(i,n){i&1&&(hi$1(0,"div",18),eD(1,6),kc());}function Qa(i,n){if(i&1&&(hi$1(0,"mat-hint",22),MD(1),kc()),i&2){let e=KE(2);Fp("id",e._hintLabelId),_v(),sh(e.hintLabel);}}function Za(i,n){if(i&1&&(hi$1(0,"div",19),AE(1,Qa,2,2,"mat-hint",22),eD(2,7),jp(3,"div",23),eD(4,8),kc()),i&2){let e=KE();_v(),RE(e.hintLabel?1:-1);}}var Nt=(()=>{class i{static \u0275fac=function(t){return new(t||i)};static \u0275dir=dE({type:i,selectors:[["mat-label"]]})}return i})(),Ci=new N("MatError"),Ya=(()=>{class i{id=T(ye$1).getId("mat-mdc-error-");static \u0275fac=function(t){return new(t||i)};static \u0275dir=dE({type:i,selectors:[["mat-error"],["","matError",""]],hostAttrs:[1,"mat-mdc-form-field-error","mat-mdc-form-field-bottom-align"],hostVars:1,hostBindings:function(t,a){t&2&&$p("id",a.id);},inputs:{id:"id"},features:[PD([{provide:Ci,useExisting:i}])]})}return i})(),Lt=(()=>{class i{align="start";id=T(ye$1).getId("mat-mdc-hint-");static \u0275fac=function(t){return new(t||i)};static \u0275dir=dE({type:i,selectors:[["mat-hint"]],hostAttrs:[1,"mat-mdc-form-field-hint","mat-mdc-form-field-bottom-align"],hostVars:4,hostBindings:function(t,a){t&2&&($p("id",a.id),Lp("align",null),Xp("mat-mdc-form-field-hint-end",a.align==="end"));},inputs:{align:"align",id:"id"}})}return i})(),Xa=new N("MatPrefix");var Di=new N("MatSuffix"),Ka=(()=>{class i{set _isTextSelector(e){this._isText=true;}_isText=false;static \u0275fac=function(t){return new(t||i)};static \u0275dir=dE({type:i,selectors:[["","matSuffix",""],["","matIconSuffix",""],["","matTextSuffix",""]],inputs:{_isTextSelector:[0,"matTextSuffix","_isTextSelector"]},features:[PD([{provide:Di,useExisting:i}])]})}return i})(),wi=new N("FloatingLabelParent"),bi=(()=>{class i{_elementRef=T(vr$1);get floating(){return this._floating}set floating(e){this._floating=e,this.monitorResize&&this._handleResize();}_floating=false;get monitorResize(){return this._monitorResize}set monitorResize(e){this._monitorResize=e,this._monitorResize?this._subscribeToResize():this._resizeSubscription.unsubscribe();}_monitorResize=false;_resizeObserver=T(st);_ngZone=T(_e);_parent=T(wi);_resizeSubscription=new j;ngOnDestroy(){this._resizeSubscription.unsubscribe();}getWidth(){return Ja(this._elementRef.nativeElement)}get element(){return this._elementRef.nativeElement}_handleResize(){setTimeout(()=>this._parent._handleLabelResized());}_subscribeToResize(){this._resizeSubscription.unsubscribe(),this._ngZone.runOutsideAngular(()=>{this._resizeSubscription=this._resizeObserver.observe(this._elementRef.nativeElement,{box:"border-box"}).subscribe(()=>this._handleResize());});}static \u0275fac=function(t){return new(t||i)};static \u0275dir=dE({type:i,selectors:[["label","matFormFieldFloatingLabel",""]],hostAttrs:[1,"mdc-floating-label","mat-mdc-floating-label"],hostVars:2,hostBindings:function(t,a){t&2&&Xp("mdc-floating-label--float-above",a.floating);},inputs:{floating:"floating",monitorResize:"monitorResize"}})}return i})();function Ja(i){let n=i;if(n.offsetParent!==null)return n.scrollWidth;let e=n.cloneNode(true);e.style.setProperty("position","absolute"),e.style.setProperty("transform","translate(-9999px, -9999px)"),document.documentElement.appendChild(e);let t=e.scrollWidth;return e.remove(),t}var _i="mdc-line-ripple--active",lt="mdc-line-ripple--deactivating",gi=(()=>{class i{_elementRef=T(vr$1);_cleanupTransitionEnd;constructor(){let e=T(_e),t=T(lI);e.runOutsideAngular(()=>{this._cleanupTransitionEnd=t.listen(this._elementRef.nativeElement,"transitionend",this._handleTransitionEnd);});}activate(){let e=this._elementRef.nativeElement.classList;e.remove(lt),e.add(_i);}deactivate(){this._elementRef.nativeElement.classList.add(lt);}_handleTransitionEnd=e=>{let t=this._elementRef.nativeElement.classList,a=t.contains(lt);e.propertyName==="opacity"&&a&&t.remove(_i,lt);};ngOnDestroy(){this._cleanupTransitionEnd();}static \u0275fac=function(t){return new(t||i)};static \u0275dir=dE({type:i,selectors:[["div","matFormFieldLineRipple",""]],hostAttrs:[1,"mdc-line-ripple"]})}return i})(),vi=(()=>{class i{_elementRef=T(vr$1);_ngZone=T(_e);open=false;_notch;ngAfterViewInit(){let e=this._elementRef.nativeElement,t=e.querySelector(".mdc-floating-label");t?(e.classList.add("mdc-notched-outline--upgraded"),typeof requestAnimationFrame=="function"&&(t.style.transitionDuration="0s",this._ngZone.runOutsideAngular(()=>{requestAnimationFrame(()=>t.style.transitionDuration="");}))):e.classList.add("mdc-notched-outline--no-label");}_setNotchWidth(e){let t=this._notch.nativeElement;!this.open||!e?t.style.width="":t.style.width=`calc(${e}px * var(--mat-mdc-form-field-floating-label-scale, 0.75) + 9px)`;}_setMaxWidth(e){this._notch.nativeElement.style.setProperty("--mat-form-field-notch-max-width",`calc(100% - ${e}px)`);}static \u0275fac=function(t){return new(t||i)};static \u0275cmp=sE({type:i,selectors:[["div","matFormFieldNotchedOutline",""]],viewQuery:function(t,a){if(t&1&&zp(Fa,5),t&2){let r;nD(r=rD())&&(a._notch=r.first);}},hostAttrs:[1,"mdc-notched-outline"],hostVars:2,hostBindings:function(t,a){t&2&&Xp("mdc-notched-outline--notched",a.open);},inputs:{open:[0,"matFormFieldNotchedOutlineOpen","open"]},ngContentSelectors:Ta,decls:5,vars:0,consts:[["notch",""],[1,"mat-mdc-notch-piece","mdc-notched-outline__leading"],[1,"mat-mdc-notch-piece","mdc-notched-outline__notch"],[1,"mat-mdc-notch-piece","mdc-notched-outline__trailing"]],template:function(t,a){t&1&&(XE(),Vp(0,"div",1),Oc(1,"div",2,0),eD(3),Pc(),Vp(4,"div",3));},encapsulation:2})}return i})(),er=(()=>{class i{value=null;stateChanges;id;placeholder;ngControl=null;focused=false;empty=false;shouldLabelFloat=false;required=false;disabled=false;errorState=false;controlType;autofilled;userAriaDescribedBy;disableAutomaticLabeling;describedByIds;static \u0275fac=function(t){return new(t||i)};static \u0275dir=dE({type:i})}return i})();var tr=new N("MatFormField"),nr=new N("MAT_FORM_FIELD_DEFAULT_OPTIONS"),yi="fill",ir="auto",xi="fixed",ar="translateY(-50%)",rr=(()=>{class i{_elementRef=T(vr$1);_changeDetectorRef=T(YF);_platform=T(_);_idGenerator=T(ye$1);_ngZone=T(_e);_defaults=T(nr,{optional:true});_currentDirection;_textField;_iconPrefixContainer;_textPrefixContainer;_iconSuffixContainer;_textSuffixContainer;_floatingLabel;_notchedOutline;_lineRipple;_iconPrefixContainerSignal=zF("iconPrefixContainer");_textPrefixContainerSignal=zF("textPrefixContainer");_iconSuffixContainerSignal=zF("iconSuffixContainer");_textSuffixContainerSignal=zF("textSuffixContainer");_prefixSuffixContainers=ZD(()=>[this._iconPrefixContainerSignal(),this._textPrefixContainerSignal(),this._iconSuffixContainerSignal(),this._textSuffixContainerSignal()].map(e=>e?.nativeElement).filter(e=>e!==void 0));_formFieldControl;_prefixChildren;_suffixChildren;_errorChildren;_hintChildren;_labelChild=QF(Nt);get hideRequiredMarker(){return this._hideRequiredMarker}set hideRequiredMarker(e){this._hideRequiredMarker=Hs(e);}_hideRequiredMarker=false;color="primary";get floatLabel(){return this._floatLabel||this._defaults?.floatLabel||ir}set floatLabel(e){e!==this._floatLabel&&(this._floatLabel=e,this._changeDetectorRef.markForCheck());}_floatLabel;get appearance(){return this._appearanceSignal()}set appearance(e){let t=e||this._defaults?.appearance||yi;this._appearanceSignal.set(t);}_appearanceSignal=jo(yi);get subscriptSizing(){return this._subscriptSizing||this._defaults?.subscriptSizing||xi}set subscriptSizing(e){this._subscriptSizing=e||this._defaults?.subscriptSizing||xi;}_subscriptSizing=null;get hintLabel(){return this._hintLabel}set hintLabel(e){this._hintLabel=e,this._processHints();}_hintLabel="";_hasIconPrefix=false;_hasTextPrefix=false;_hasIconSuffix=false;_hasTextSuffix=false;_labelId=this._idGenerator.getId("mat-mdc-form-field-label-");_hintLabelId=this._idGenerator.getId("mat-mdc-hint-");_describedByIds;get _control(){return this._explicitFormFieldControl||this._formFieldControl}set _control(e){this._explicitFormFieldControl=e;}_destroyed=new ee;_isFocused=null;_explicitFormFieldControl;_previousControl=null;_previousControlValidatorFn=null;_stateChanges;_valueChanges;_describedByChanges;_outlineLabelOffsetResizeObserver=null;_animationsDisabled=nt$1();constructor(){let e=this._defaults,t=T(Er$1);e&&(e.appearance&&(this.appearance=e.appearance),this._hideRequiredMarker=!!e?.hideRequiredMarker,e.color&&(this.color=e.color)),Gu(()=>this._currentDirection=t.valueSignal()),this._syncOutlineLabelOffset();}ngAfterViewInit(){this._updateFocusState(),this._animationsDisabled||this._ngZone.runOutsideAngular(()=>{setTimeout(()=>{this._elementRef.nativeElement.classList.add("mat-form-field-animations-enabled");},300);}),this._changeDetectorRef.detectChanges();}ngAfterContentInit(){this._assertFormFieldControl(),this._initializeSubscript(),this._initializePrefixAndSuffix();}ngAfterContentChecked(){this._assertFormFieldControl(),this._control!==this._previousControl&&(this._initializeControl(this._previousControl),this._control.ngControl&&this._control.ngControl.control&&(this._previousControlValidatorFn=this._control.ngControl.control.validator),this._previousControl=this._control),this._control.ngControl&&this._control.ngControl.control&&this._control.ngControl.control.validator!==this._previousControlValidatorFn&&this._changeDetectorRef.markForCheck();}ngOnDestroy(){this._outlineLabelOffsetResizeObserver?.disconnect(),this._stateChanges?.unsubscribe(),this._valueChanges?.unsubscribe(),this._describedByChanges?.unsubscribe(),this._destroyed.next(),this._destroyed.complete();}getLabelId=ZD(()=>this._hasFloatingLabel()?this._labelId:null);getConnectedOverlayOrigin(){return this._textField||this._elementRef}_animateAndLockLabel(){this._hasFloatingLabel()&&(this.floatLabel="always");}_initializeControl(e){let t=this._control,a="mat-mdc-form-field-type-";e&&this._elementRef.nativeElement.classList.remove(a+e.controlType),t.controlType&&this._elementRef.nativeElement.classList.add(a+t.controlType),this._stateChanges?.unsubscribe(),this._stateChanges=t.stateChanges.subscribe(()=>{this._updateFocusState(),this._changeDetectorRef.markForCheck();}),this._describedByChanges?.unsubscribe(),this._describedByChanges=t.stateChanges.pipe(Eg([void 0,void 0]),we$1(()=>[t.errorState,t.userAriaDescribedBy]),yg(),Gt$1(([[r,o],[p,C]])=>r!==p||o!==C)).subscribe(()=>this._syncDescribedByIds()),this._valueChanges?.unsubscribe(),t.ngControl&&t.ngControl.valueChanges&&(this._valueChanges=t.ngControl.valueChanges.pipe(Dg(this._destroyed)).subscribe(()=>this._changeDetectorRef.markForCheck()));}_checkPrefixAndSuffixTypes(){this._hasIconPrefix=!!this._prefixChildren.find(e=>!e._isText),this._hasTextPrefix=!!this._prefixChildren.find(e=>e._isText),this._hasIconSuffix=!!this._suffixChildren.find(e=>!e._isText),this._hasTextSuffix=!!this._suffixChildren.find(e=>e._isText);}_initializePrefixAndSuffix(){this._checkPrefixAndSuffixTypes(),ug(this._prefixChildren.changes,this._suffixChildren.changes).subscribe(()=>{this._checkPrefixAndSuffixTypes(),this._changeDetectorRef.markForCheck();});}_initializeSubscript(){this._hintChildren.changes.subscribe(()=>{this._processHints(),this._changeDetectorRef.markForCheck();}),this._errorChildren.changes.subscribe(()=>{this._syncDescribedByIds(),this._changeDetectorRef.markForCheck();}),this._validateHints(),this._syncDescribedByIds();}_assertFormFieldControl(){this._control;}_updateFocusState(){let e=this._control.focused;e&&!this._isFocused?(this._isFocused=true,this._lineRipple?.activate()):!e&&(this._isFocused||this._isFocused===null)&&(this._isFocused=false,this._lineRipple?.deactivate()),this._elementRef.nativeElement.classList.toggle("mat-focused",e),this._textField?.nativeElement.classList.toggle("mdc-text-field--focused",e);}_syncOutlineLabelOffset(){e0({earlyRead:()=>{if(this._appearanceSignal()!=="outline")return this._outlineLabelOffsetResizeObserver?.disconnect(),null;if(globalThis.ResizeObserver){this._outlineLabelOffsetResizeObserver||=new globalThis.ResizeObserver(()=>{this._writeOutlinedLabelStyles(this._getOutlinedLabelOffset());});for(let e of this._prefixSuffixContainers())this._outlineLabelOffsetResizeObserver.observe(e,{box:"border-box"});}return this._getOutlinedLabelOffset()},write:e=>this._writeOutlinedLabelStyles(e())});}_shouldAlwaysFloat(){return this.floatLabel==="always"}_hasOutline(){return this.appearance==="outline"}_forceDisplayInfixLabel(){return !this._platform.isBrowser&&this._prefixChildren.length&&!this._shouldLabelFloat()}_hasFloatingLabel=ZD(()=>!!this._labelChild());_shouldLabelFloat(){return this._hasFloatingLabel()?this._control.shouldLabelFloat||this._shouldAlwaysFloat():false}_shouldForward(e){let t=this._control?this._control.ngControl:null;return t&&t[e]}_getSubscriptMessageType(){return this._errorChildren&&this._errorChildren.length>0&&this._control.errorState?"error":"hint"}_handleLabelResized(){this._refreshOutlineNotchWidth();}_refreshOutlineNotchWidth(){!this._hasOutline()||!this._floatingLabel||!this._shouldLabelFloat()?this._notchedOutline?._setNotchWidth(0):this._notchedOutline?._setNotchWidth(this._floatingLabel.getWidth());}_processHints(){this._validateHints(),this._syncDescribedByIds();}_validateHints(){this._hintChildren;}_syncDescribedByIds(){if(this._control){let e=[];if(this._control.userAriaDescribedBy&&typeof this._control.userAriaDescribedBy=="string"&&e.push(...this._control.userAriaDescribedBy.split(" ")),this._getSubscriptMessageType()==="hint"){let r=this._hintChildren?this._hintChildren.find(p=>p.align==="start"):null,o=this._hintChildren?this._hintChildren.find(p=>p.align==="end"):null;r?e.push(r.id):this._hintLabel&&e.push(this._hintLabelId),o&&e.push(o.id);}else this._errorChildren&&e.push(...this._errorChildren.map(r=>r.id));let t=this._control.describedByIds,a;if(t){let r=this._describedByIds||e;a=e.concat(t.filter(o=>o&&!r.includes(o)));}else a=e;this._control.setDescribedByIds(a),this._describedByIds=e;}}_getOutlinedLabelOffset(){if(!this._hasOutline()||!this._floatingLabel)return null;if(!this._iconPrefixContainer&&!this._textPrefixContainer)return ["",null];if(!this._isAttachedToDom())return null;let e=this._iconPrefixContainer?.nativeElement,t=this._textPrefixContainer?.nativeElement,a=this._iconSuffixContainer?.nativeElement,r=this._textSuffixContainer?.nativeElement,o=e?.getBoundingClientRect().width??0,p=t?.getBoundingClientRect().width??0,C=a?.getBoundingClientRect().width??0,U=r?.getBoundingClientRect().width??0,Y=this._currentDirection==="rtl"?"-1":"1",ke=`${o+p}px`,Pi=`calc(${Y} * (${ke} + var(--mat-mdc-form-field-label-offset-x, 0px)))`,Oi=`var(--mat-mdc-form-field-label-transform, ${ar} translateX(${Pi}))`,Ni=o+p+C+U;return [Oi,Ni]}_writeOutlinedLabelStyles(e){if(e!==null){let[t,a]=e;this._floatingLabel&&(this._floatingLabel.element.style.transform=t),a!==null&&this._notchedOutline?._setMaxWidth(a);}}_isAttachedToDom(){let e=this._elementRef.nativeElement;if(e.getRootNode){let t=e.getRootNode();return t&&t!==e}return document.documentElement.contains(e)}static \u0275fac=function(t){return new(t||i)};static \u0275cmp=sE({type:i,selectors:[["mat-form-field"]],contentQueries:function(t,a,r){if(t&1&&(Qp(r,a._labelChild,Nt,5),Gp(r,er,5)(r,Xa,5)(r,Di,5)(r,Ci,5)(r,Lt,5)),t&2){oD();let o;nD(o=rD())&&(a._formFieldControl=o.first),nD(o=rD())&&(a._prefixChildren=o),nD(o=rD())&&(a._suffixChildren=o),nD(o=rD())&&(a._errorChildren=o),nD(o=rD())&&(a._hintChildren=o);}},viewQuery:function(t,a){if(t&1&&(Zp(a._iconPrefixContainerSignal,ui,5)(a._textPrefixContainerSignal,fi,5)(a._iconSuffixContainerSignal,hi,5)(a._textSuffixContainerSignal,pi,5),zp(Aa,5)(ui,5)(fi,5)(hi,5)(pi,5)(bi,5)(vi,5)(gi,5)),t&2){oD(4);let r;nD(r=rD())&&(a._textField=r.first),nD(r=rD())&&(a._iconPrefixContainer=r.first),nD(r=rD())&&(a._textPrefixContainer=r.first),nD(r=rD())&&(a._iconSuffixContainer=r.first),nD(r=rD())&&(a._textSuffixContainer=r.first),nD(r=rD())&&(a._floatingLabel=r.first),nD(r=rD())&&(a._notchedOutline=r.first),nD(r=rD())&&(a._lineRipple=r.first);}},hostAttrs:[1,"mat-mdc-form-field"],hostVars:38,hostBindings:function(t,a){t&2&&Xp("mat-mdc-form-field-label-always-float",a._shouldAlwaysFloat())("mat-mdc-form-field-has-icon-prefix",a._hasIconPrefix)("mat-mdc-form-field-has-icon-suffix",a._hasIconSuffix)("mat-form-field-invalid",a._control.errorState)("mat-form-field-disabled",a._control.disabled)("mat-form-field-autofilled",a._control.autofilled)("mat-form-field-appearance-fill",a.appearance=="fill")("mat-form-field-appearance-outline",a.appearance=="outline")("mat-form-field-hide-placeholder",a._hasFloatingLabel()&&!a._shouldLabelFloat())("mat-primary",a.color!=="accent"&&a.color!=="warn")("mat-accent",a.color==="accent")("mat-warn",a.color==="warn")("ng-untouched",a._shouldForward("untouched"))("ng-touched",a._shouldForward("touched"))("ng-pristine",a._shouldForward("pristine"))("ng-dirty",a._shouldForward("dirty"))("ng-valid",a._shouldForward("valid"))("ng-invalid",a._shouldForward("invalid"))("ng-pending",a._shouldForward("pending"));},inputs:{hideRequiredMarker:"hideRequiredMarker",color:"color",floatLabel:"floatLabel",appearance:"appearance",subscriptSizing:"subscriptSizing",hintLabel:"hintLabel"},exportAs:["matFormField"],features:[PD([{provide:tr,useExisting:i},{provide:wi,useExisting:i}])],ngContentSelectors:Sa,decls:18,vars:21,consts:[["labelTemplate",""],["textField",""],["iconPrefixContainer",""],["textPrefixContainer",""],["textSuffixContainer",""],["iconSuffixContainer",""],[1,"mat-mdc-text-field-wrapper","mdc-text-field",3,"click"],[1,"mat-mdc-form-field-focus-overlay"],[1,"mat-mdc-form-field-flex"],["matFormFieldNotchedOutline","",3,"matFormFieldNotchedOutlineOpen"],[1,"mat-mdc-form-field-icon-prefix"],[1,"mat-mdc-form-field-text-prefix"],[1,"mat-mdc-form-field-infix"],[3,"ngTemplateOutlet"],[1,"mat-mdc-form-field-text-suffix"],[1,"mat-mdc-form-field-icon-suffix"],["matFormFieldLineRipple",""],["aria-atomic","true","aria-live","polite",1,"mat-mdc-form-field-subscript-wrapper","mat-mdc-form-field-bottom-align"],[1,"mat-mdc-form-field-error-wrapper"],[1,"mat-mdc-form-field-hint-wrapper"],["matFormFieldFloatingLabel","",3,"floating","monitorResize","id"],["aria-hidden","true",1,"mat-mdc-form-field-required-marker","mdc-floating-label--required"],[3,"id"],[1,"mat-mdc-form-field-hint-spacer"]],template:function(t,a){if(t&1&&(XE(Ia),xp(0,Pa,1,1,"ng-template",null,0,WD),hi$1(2,"div",6,1),qp("click",function(o){return a._control.onContainerClick(o)}),AE(4,Oa,1,0,"div",7),hi$1(5,"div",8),AE(6,Ba,2,2,"div",9),AE(7,za,3,0,"div",10),AE(8,ja,3,0,"div",11),hi$1(9,"div",12),AE(10,Ga,1,1,null,13),eD(11),kc(),AE(12,qa,3,0,"div",14),AE(13,Wa,3,0,"div",15),kc(),AE(14,Ua,1,0,"div",16),kc(),hi$1(15,"div",17),AE(16,$a,2,0,"div",18)(17,Za,5,1,"div",19),kc()),t&2){let r;_v(2),Xp("mdc-text-field--filled",!a._hasOutline())("mdc-text-field--outlined",a._hasOutline())("mdc-text-field--no-label",!a._hasFloatingLabel())("mdc-text-field--disabled",a._control.disabled)("mdc-text-field--invalid",a._control.errorState),_v(2),RE(!a._hasOutline()&&!a._control.disabled?4:-1),_v(2),RE(a._hasOutline()?6:-1),_v(),RE(a._hasIconPrefix?7:-1),_v(),RE(a._hasTextPrefix?8:-1),_v(2),RE(!a._hasOutline()||a._forceDisplayInfixLabel()?10:-1),_v(2),RE(a._hasTextSuffix?12:-1),_v(),RE(a._hasIconSuffix?13:-1),_v(),RE(a._hasOutline()?-1:14),_v(),Xp("mat-mdc-form-field-subscript-dynamic-size",a.subscriptSizing==="dynamic");let o=a._getSubscriptMessageType();_v(),RE((r=o)==="error"?16:r==="hint"?17:-1);}},dependencies:[bi,vi,jn$1,gi,Lt],styles:[`.mdc-text-field {
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
`],encapsulation:2})}return i})();var Bt=class{_multiple;_emitChanges;compareWith;_selection=new Set;_deselectedToEmit=[];_selectedToEmit=[];_selected=null;get selected(){return this._selected||(this._selected=Array.from(this._selection.values())),this._selected}changed=new ee;bulk={select:n=>this._select(n),deselect:n=>this._deselect(n),setSelection:n=>this._setSelection(n)};constructor(n=false,e,t=true,a){this._multiple=n,this._emitChanges=t,this.compareWith=a,e&&e.length&&(n?e.forEach(r=>this._markSelected(r)):this._markSelected(e[0]),this._selectedToEmit.length=0);}select(...n){return this._select(n)}deselect(...n){return this._deselect(n)}setSelection(...n){return this._setSelection(n)}toggle(n){return this.isSelected(n)?this.deselect(n):this.select(n)}clear(n=true){this._unmarkAll();let e=this._hasQueuedChanges();return n&&this._emitChangeEvent(),e}isSelected(n){return this._selection.has(this._getConcreteValue(n))}isEmpty(){return this._selection.size===0}hasValue(){return !this.isEmpty()}sort(n){this._multiple&&this.selected&&this._selected.sort(n);}isMultipleSelection(){return this._multiple}_select(n){this._verifyValueAssignment(n),n.forEach(t=>this._markSelected(t));let e=this._hasQueuedChanges();return this._emitChangeEvent(),e}_deselect(n){this._verifyValueAssignment(n),n.forEach(t=>this._unmarkSelected(t));let e=this._hasQueuedChanges();return this._emitChangeEvent(),e}_setSelection(n){this._verifyValueAssignment(n);let e=this.selected,t=new Set(n.map(r=>this._getConcreteValue(r)));n.forEach(r=>this._markSelected(r)),e.filter(r=>!t.has(this._getConcreteValue(r,t))).forEach(r=>this._unmarkSelected(r));let a=this._hasQueuedChanges();return this._emitChangeEvent(),a}_emitChangeEvent(){this._selected=null,(this._selectedToEmit.length||this._deselectedToEmit.length)&&(this.changed.next({source:this,added:this._selectedToEmit,removed:this._deselectedToEmit}),this._deselectedToEmit=[],this._selectedToEmit=[]);}_markSelected(n){n=this._getConcreteValue(n),this.isSelected(n)||(this._multiple||this._unmarkAll(),this.isSelected(n)||this._selection.add(n),this._emitChanges&&this._selectedToEmit.push(n));}_unmarkSelected(n){n=this._getConcreteValue(n),this.isSelected(n)&&(this._selection.delete(n),this._emitChanges&&this._deselectedToEmit.push(n));}_unmarkAll(){this.isEmpty()||this._selection.forEach(n=>this._unmarkSelected(n));}_verifyValueAssignment(n){n.length>1&&this._multiple;}_hasQueuedChanges(){return !!(this._deselectedToEmit.length||this._selectedToEmit.length)}_getConcreteValue(n,e){if(this.compareWith){e=e??this._selection;for(let t of e)if(this.compareWith(n,t))return t;return n}else return n}};var zt=(()=>{class i{_listeners=[];notify(e,t){for(let a of this._listeners)a(e,t);}listen(e){return this._listeners.push(e),()=>{this._listeners=this._listeners.filter(t=>e!==t);}}ngOnDestroy(){this._listeners=[];}static \u0275fac=function(t){return new(t||i)};static \u0275prov=yr$1({token:i,factory:i.\u0275fac})}return i})();var ki=class{applyChanges(n,e,t,a,r){n.forEachOperation((o,p,C)=>{let U,Y;if(o.previousIndex==null){let ke=t(o,p,C);U=e.createEmbeddedView(ke.templateRef,ke.context,ke.index),Y=_$1.INSERTED;}else C==null?(e.remove(p),Y=_$1.REMOVED):(U=e.get(p),e.move(U,C),Y=_$1.MOVED);r&&r({context:U?.context,operation:Y,record:o});});}detach(){}};var or=["*"],Mi=(()=>{class i{labelPosition="after";static \u0275fac=function(t){return new(t||i)};static \u0275cmp=sE({type:i,selectors:[["div","mat-internal-form-field",""]],hostAttrs:[1,"mdc-form-field","mat-internal-form-field"],hostVars:2,hostBindings:function(t,a){t&2&&Xp("mdc-form-field--align-end",a.labelPosition==="before");},inputs:{labelPosition:"labelPosition"},ngContentSelectors:or,decls:1,vars:0,template:function(t,a){t&1&&(XE(),eD(0));},styles:[`.mat-internal-form-field {
  -moz-osx-font-smoothing: grayscale;
  -webkit-font-smoothing: antialiased;
  display: inline-flex;
  align-items: center;
  vertical-align: middle;
}
.mat-internal-form-field > label {
  margin-left: 0;
  margin-right: auto;
  padding-left: 4px;
  padding-right: 0;
  order: 0;
}
[dir=rtl] .mat-internal-form-field > label {
  margin-left: auto;
  margin-right: 0;
  padding-left: 0;
  padding-right: 4px;
}

.mdc-form-field--align-end > label {
  margin-left: auto;
  margin-right: 0;
  padding-left: 0;
  padding-right: 4px;
  order: -1;
}
[dir=rtl] .mdc-form-field--align-end .mdc-form-field--align-end label {
  margin-left: 0;
  margin-right: auto;
  padding-left: 4px;
  padding-right: 0;
}
`],encapsulation:2})}return i})();var sr=["input"],lr=["formField"],dr=["*"],dt=class{source;value;constructor(n,e){this.source=n,this.value=e;}},cr={provide:De,useExisting:yo(()=>ur),multi:true},Ei=new N("MatRadioGroup"),mr=new N("mat-radio-default-options",{providedIn:"root",factory:()=>({color:"accent",disabledInteractive:false})}),ur=(()=>{class i{_changeDetector=T(YF);_value=null;_name=T(ye$1).getId("mat-radio-group-");_selected=null;_isInitialized=false;_labelPosition="after";_disabled=false;_required=false;_buttonChanges;_controlValueAccessorChangeFn=()=>{};onTouched=()=>{};change=new He;_radios;color;get name(){return this._name}set name(e){this._name=e,this._updateRadioButtonNames();}get labelPosition(){return this._labelPosition}set labelPosition(e){this._labelPosition=e==="before"?"before":"after",this._markRadiosForCheck();}get value(){return this._value}set value(e){this._value!==e&&(this._value=e,this._updateSelectedRadioFromValue(),this._checkSelectedRadioButton());}_checkSelectedRadioButton(){this._selected&&!this._selected.checked&&(this._selected.checked=true);}get selected(){return this._selected}set selected(e){this._selected=e,this.value=e?e.value:null,this._checkSelectedRadioButton();}get disabled(){return this._disabled}set disabled(e){this._disabled=e,this._markRadiosForCheck();}get required(){return this._required}set required(e){this._required=e,this._markRadiosForCheck();}get disabledInteractive(){return this._disabledInteractive}set disabledInteractive(e){this._disabledInteractive=e,this._markRadiosForCheck();}_disabledInteractive=false;ngAfterContentInit(){this._isInitialized=true,this._buttonChanges=this._radios.changes.subscribe(()=>{this.selected&&!this._radios.find(e=>e===this.selected)&&(this._selected=null);});}ngOnDestroy(){this._buttonChanges?.unsubscribe();}_touch(){this.onTouched&&this.onTouched();}_updateRadioButtonNames(){this._radios&&this._radios.forEach(e=>{e.name=this.name,e._markForCheck();});}_updateSelectedRadioFromValue(){let e=this._selected!==null&&this._selected.value===this._value;this._radios&&!e&&(this._selected=null,this._radios.forEach(t=>{t.checked=this.value===t.value,t.checked&&(this._selected=t);}));}_emitChangeEvent(){this._isInitialized&&this.change.emit(new dt(this._selected,this._value));}_markRadiosForCheck(){this._radios&&this._radios.forEach(e=>e._markForCheck());}writeValue(e){this.value=e,this._changeDetector.markForCheck();}registerOnChange(e){this._controlValueAccessorChangeFn=e;}registerOnTouched(e){this.onTouched=e;}setDisabledState(e){this.disabled=e,this._changeDetector.markForCheck();}static \u0275fac=function(t){return new(t||i)};static \u0275dir=dE({type:i,selectors:[["mat-radio-group"]],contentQueries:function(t,a,r){if(t&1&&Gp(r,fr,5),t&2){let o;nD(o=rD())&&(a._radios=o);}},hostAttrs:["role","radiogroup",1,"mat-mdc-radio-group"],inputs:{color:"color",name:"name",labelPosition:"labelPosition",value:"value",selected:"selected",disabled:[2,"disabled","disabled",JF],required:[2,"required","required",JF],disabledInteractive:[2,"disabledInteractive","disabledInteractive",JF]},outputs:{change:"change"},exportAs:["matRadioGroup"],features:[PD([cr,{provide:Ei,useExisting:i}])]})}return i})(),fr=(()=>{class i{_elementRef=T(vr$1);_changeDetector=T(YF);_focusMonitor=T(Pt$1);_radioDispatcher=T(zt);_defaultOptions=T(mr,{optional:true});_ngZone=T(_e);_renderer=T(lI);_uniqueId=T(ye$1).getId("mat-radio-");_cleanupClick;id=this._uniqueId;name;ariaLabel;ariaLabelledby;ariaDescribedby;disableRipple=false;tabIndex=0;get checked(){return this._checked}set checked(e){this._checked!==e&&(this._checked=e,e&&this.radioGroup&&this.radioGroup.value!==this.value?this.radioGroup.selected=this:!e&&this.radioGroup&&this.radioGroup.value===this.value&&(this.radioGroup.selected=null),e&&this._radioDispatcher.notify(this.id,this.name),this._changeDetector.markForCheck());}get value(){return this._value}set value(e){this._value!==e&&(this._value=e,this.radioGroup!==null&&(this.checked||(this.checked=this.radioGroup.value===e),this.checked&&(this.radioGroup.selected=this)));}get labelPosition(){return this._labelPosition||this.radioGroup&&this.radioGroup.labelPosition||"after"}set labelPosition(e){this._labelPosition=e;}_labelPosition;get disabled(){return this._disabled||this.radioGroup!==null&&this.radioGroup.disabled}set disabled(e){this._setDisabled(e);}get required(){return this._required||this.radioGroup&&this.radioGroup.required}set required(e){e!==this._required&&this._changeDetector.markForCheck(),this._required=e;}get color(){return this._color||this.radioGroup&&this.radioGroup.color||this._defaultOptions&&this._defaultOptions.color||"accent"}set color(e){this._color=e;}_color;get disabledInteractive(){return this._disabledInteractive||this.radioGroup!==null&&this.radioGroup.disabledInteractive}set disabledInteractive(e){this._disabledInteractive=e;}_disabledInteractive;change=new He;radioGroup;get inputId(){return `${this.id||this._uniqueId}-input`}_checked=false;_disabled=false;_required=false;_value=null;_removeUniqueSelectionListener=()=>{};_previousTabIndex;_inputElement;_rippleTrigger;_noopAnimations=nt$1();_injector=T(pe);constructor(){T(F).load(oo);let e=T(Ei,{optional:true}),t=T(new gh("tabindex"),{optional:true});this.radioGroup=e,this._disabledInteractive=this._defaultOptions?.disabledInteractive??false,t&&(this.tabIndex=XF(t,0));}focus(e,t){t?this._focusMonitor.focusVia(this._inputElement,t,e):this._inputElement.nativeElement.focus(e);}_markForCheck(){this._changeDetector.markForCheck();}ngOnInit(){this.radioGroup&&(this.checked=this.radioGroup.value===this._value,this.checked&&(this.radioGroup.selected=this),this.name=this.radioGroup.name),this._removeUniqueSelectionListener=this._radioDispatcher.listen((e,t)=>{e!==this.id&&t===this.name&&(this.checked=false);});}ngDoCheck(){this._updateTabIndex();}ngAfterViewInit(){this._updateTabIndex(),this._focusMonitor.monitor(this._elementRef,true).subscribe(e=>{!e&&this.radioGroup&&this.radioGroup._touch();}),this._ngZone.runOutsideAngular(()=>{this._cleanupClick=this._renderer.listen(this._inputElement.nativeElement,"click",this._onInputClick);});}ngOnDestroy(){this._cleanupClick?.(),this._focusMonitor.stopMonitoring(this._elementRef),this._removeUniqueSelectionListener();}_emitChangeEvent(){this.change.emit(new dt(this,this._value));}_isRippleDisabled(){return this.disableRipple||this.disabled}_onInputInteraction(e){if(e.stopPropagation(),!this.checked&&!this.disabled){let t=this.radioGroup&&this.value!==this.radioGroup.value;this.checked=true,this._emitChangeEvent(),this.radioGroup&&(this.radioGroup._controlValueAccessorChangeFn(this.value),t&&this.radioGroup._emitChangeEvent());}}_onTouchTargetClick(e){this._onInputInteraction(e),(!this.disabled||this.disabledInteractive)&&this._inputElement?.nativeElement.focus();}_setDisabled(e){this._disabled!==e&&(this._disabled=e,this._changeDetector.markForCheck());}_onInputClick=e=>{this.disabled&&this.disabledInteractive&&e.preventDefault();};_updateTabIndex(){let e=this.radioGroup,t;if(!e||!e.selected||this.disabled?t=this.tabIndex:t=e.selected===this?this.tabIndex:-1,t!==this._previousTabIndex){let a=this._inputElement?.nativeElement;a&&(a.setAttribute("tabindex",t+""),this._previousTabIndex=t,nv(()=>{queueMicrotask(()=>{e&&e.selected&&e.selected!==this&&document.activeElement===a&&(e.selected?._inputElement.nativeElement.focus(),document.activeElement===a&&this._inputElement.nativeElement.blur());});},{injector:this._injector}));}}static \u0275fac=function(t){return new(t||i)};static \u0275cmp=sE({type:i,selectors:[["mat-radio-button"]],viewQuery:function(t,a){if(t&1&&zp(sr,5)(lr,7,vr$1),t&2){let r;nD(r=rD())&&(a._inputElement=r.first),nD(r=rD())&&(a._rippleTrigger=r.first);}},hostAttrs:[1,"mat-mdc-radio-button"],hostVars:19,hostBindings:function(t,a){t&1&&qp("focus",function(){return a._inputElement.nativeElement.focus()}),t&2&&(Lp("id",a.id)("tabindex",null)("aria-label",null)("aria-labelledby",null)("aria-describedby",null),Xp("mat-primary",a.color==="primary")("mat-accent",a.color==="accent")("mat-warn",a.color==="warn")("mat-mdc-radio-checked",a.checked)("mat-mdc-radio-disabled",a.disabled)("mat-mdc-radio-disabled-interactive",a.disabledInteractive)("_mat-animation-noopable",a._noopAnimations));},inputs:{id:"id",name:"name",ariaLabel:[0,"aria-label","ariaLabel"],ariaLabelledby:[0,"aria-labelledby","ariaLabelledby"],ariaDescribedby:[0,"aria-describedby","ariaDescribedby"],disableRipple:[2,"disableRipple","disableRipple",JF],tabIndex:[2,"tabIndex","tabIndex",e=>e==null?0:XF(e)],checked:[2,"checked","checked",JF],value:"value",labelPosition:"labelPosition",disabled:[2,"disabled","disabled",JF],required:[2,"required","required",JF],color:"color",disabledInteractive:[2,"disabledInteractive","disabledInteractive",JF]},outputs:{change:"change"},exportAs:["matRadioButton"],ngContentSelectors:dr,decls:13,vars:17,consts:[["formField",""],["input",""],["mat-internal-form-field","",3,"labelPosition"],[1,"mdc-radio"],["aria-hidden","true",1,"mat-mdc-radio-touch-target",3,"click"],["type","radio","aria-invalid","false",1,"mdc-radio__native-control",3,"change","id","checked","disabled","required"],["aria-hidden","true",1,"mdc-radio__background"],[1,"mdc-radio__outer-circle"],[1,"mdc-radio__inner-circle"],["mat-ripple","","aria-hidden","true",1,"mat-radio-ripple","mat-focus-indicator",3,"matRippleTrigger","matRippleDisabled","matRippleCentered"],[1,"mat-ripple-element","mat-radio-persistent-ripple"],[1,"mdc-label",3,"for"]],template:function(t,a){t&1&&(XE(),hi$1(0,"div",2,0)(2,"div",3)(3,"div",4),qp("click",function(o){return a._onTouchTargetClick(o)}),kc(),hi$1(4,"input",5,1),qp("change",function(o){return a._onInputInteraction(o)}),kc(),hi$1(6,"div",6),jp(7,"div",7)(8,"div",8),kc(),hi$1(9,"div",9),jp(10,"div",10),kc()(),hi$1(11,"label",11),eD(12),kc()()),t&2&&(Fp("labelPosition",a.labelPosition),_v(2),Xp("mdc-radio--disabled",a.disabled),_v(2),Fp("id",a.inputId)("checked",a.checked)("disabled",a.disabled&&!a.disabledInteractive)("required",a.required),Lp("name",a.name)("value",a.value)("aria-label",a.ariaLabel)("aria-labelledby",a.ariaLabelledby)("aria-describedby",a.ariaDescribedby)("aria-disabled",a.disabled&&a.disabledInteractive?"true":null),_v(5),Fp("matRippleTrigger",a._rippleTrigger.nativeElement)("matRippleDisabled",a._isRippleDisabled())("matRippleCentered",true),_v(2),Fp("for",a.inputId));},dependencies:[tc,Mi],styles:[`.mat-mdc-radio-button {
  -webkit-tap-highlight-color: transparent;
}
.mat-mdc-radio-button .mdc-radio {
  display: inline-block;
  position: relative;
  flex: 0 0 auto;
  box-sizing: content-box;
  width: 20px;
  height: 20px;
  will-change: opacity, transform, border-color, color;
  padding: calc((var(--mat-radio-state-layer-size, 40px) - 20px) / 2);
  cursor: pointer;
}
.mat-mdc-radio-button .mdc-radio:hover > .mdc-radio__native-control:not([disabled]):not(:focus) ~ .mdc-radio__background::before {
  opacity: 0.04;
  transform: scale(1);
}
.mat-mdc-radio-button .mdc-radio:hover > .mdc-radio__native-control:not([disabled]) ~ .mdc-radio__background > .mdc-radio__outer-circle {
  border-color: var(--mat-radio-unselected-hover-icon-color, var(--mat-sys-on-surface));
}
.mat-mdc-radio-button .mdc-radio:hover > .mdc-radio__native-control:enabled:checked + .mdc-radio__background > .mdc-radio__outer-circle {
  border-color: var(--mat-radio-selected-hover-icon-color, var(--mat-sys-primary));
}
.mat-mdc-radio-button .mdc-radio:hover > .mdc-radio__native-control:enabled:checked + .mdc-radio__background > .mdc-radio__inner-circle {
  background-color: var(--mat-radio-selected-hover-icon-color, var(--mat-sys-primary, currentColor));
}
.mat-mdc-radio-button .mdc-radio:active > .mdc-radio__native-control:enabled:not(:checked) + .mdc-radio__background > .mdc-radio__outer-circle {
  border-color: var(--mat-radio-unselected-pressed-icon-color, var(--mat-sys-on-surface));
}
.mat-mdc-radio-button .mdc-radio:active > .mdc-radio__native-control:enabled:checked + .mdc-radio__background > .mdc-radio__outer-circle {
  border-color: var(--mat-radio-selected-pressed-icon-color, var(--mat-sys-primary));
}
.mat-mdc-radio-button .mdc-radio:active > .mdc-radio__native-control:enabled:checked + .mdc-radio__background > .mdc-radio__inner-circle {
  background-color: var(--mat-radio-selected-pressed-icon-color, var(--mat-sys-primary, currentColor));
}
.mat-mdc-radio-button .mdc-radio__background {
  display: inline-block;
  position: relative;
  box-sizing: border-box;
  width: 20px;
  height: 20px;
}
.mat-mdc-radio-button .mdc-radio__background::before {
  position: absolute;
  transform: scale(0, 0);
  border-radius: 50%;
  opacity: 0;
  pointer-events: none;
  content: "";
  transition: opacity 90ms cubic-bezier(0.4, 0, 0.6, 1), transform 90ms cubic-bezier(0.4, 0, 0.6, 1);
  width: var(--mat-radio-state-layer-size, 40px);
  height: var(--mat-radio-state-layer-size, 40px);
  top: calc(-1 * (var(--mat-radio-state-layer-size, 40px) - 20px) / 2);
  left: calc(-1 * (var(--mat-radio-state-layer-size, 40px) - 20px) / 2);
}
.mat-mdc-radio-button .mdc-radio__outer-circle {
  position: absolute;
  top: 0;
  left: 0;
  box-sizing: border-box;
  width: 100%;
  height: 100%;
  border-width: 2px;
  border-style: solid;
  border-radius: 50%;
  transition: border-color 90ms cubic-bezier(0.4, 0, 0.6, 1);
}
.mat-mdc-radio-button .mdc-radio__inner-circle {
  position: absolute;
  top: 0;
  left: 0;
  box-sizing: border-box;
  width: 100%;
  height: 100%;
  transform: scale(0);
  border-radius: 50%;
  transition: transform 90ms cubic-bezier(0.4, 0, 0.6, 1), background-color 90ms cubic-bezier(0.4, 0, 0.6, 1);
}
@media (forced-colors: active) {
  .mat-mdc-radio-button .mdc-radio__inner-circle {
    background-color: CanvasText !important;
  }
}
.mat-mdc-radio-button .mdc-radio__native-control {
  position: absolute;
  margin: 0;
  padding: 0;
  opacity: 0;
  top: 0;
  right: 0;
  left: 0;
  cursor: inherit;
  z-index: 1;
  width: var(--mat-radio-state-layer-size, 40px);
  height: var(--mat-radio-state-layer-size, 40px);
}
.mat-mdc-radio-button .mdc-radio__native-control:checked + .mdc-radio__background, .mat-mdc-radio-button .mdc-radio__native-control:disabled + .mdc-radio__background {
  transition: opacity 90ms cubic-bezier(0, 0, 0.2, 1), transform 90ms cubic-bezier(0, 0, 0.2, 1);
}
.mat-mdc-radio-button .mdc-radio__native-control:checked + .mdc-radio__background > .mdc-radio__outer-circle, .mat-mdc-radio-button .mdc-radio__native-control:disabled + .mdc-radio__background > .mdc-radio__outer-circle {
  transition: border-color 90ms cubic-bezier(0, 0, 0.2, 1);
}
.mat-mdc-radio-button .mdc-radio__native-control:checked + .mdc-radio__background > .mdc-radio__inner-circle, .mat-mdc-radio-button .mdc-radio__native-control:disabled + .mdc-radio__background > .mdc-radio__inner-circle {
  transition: transform 90ms cubic-bezier(0, 0, 0.2, 1), background-color 90ms cubic-bezier(0, 0, 0.2, 1);
}
.mat-mdc-radio-button .mdc-radio__native-control:focus + .mdc-radio__background::before {
  transform: scale(1);
  opacity: 0.12;
  transition: opacity 90ms cubic-bezier(0, 0, 0.2, 1), transform 90ms cubic-bezier(0, 0, 0.2, 1);
}
.mat-mdc-radio-button .mdc-radio__native-control:disabled:not(:checked) + .mdc-radio__background > .mdc-radio__outer-circle {
  border-color: var(--mat-radio-disabled-unselected-icon-color, var(--mat-sys-on-surface));
  opacity: var(--mat-radio-disabled-unselected-icon-opacity, 0.38);
}
.mat-mdc-radio-button .mdc-radio__native-control:disabled + .mdc-radio__background {
  cursor: default;
}
.mat-mdc-radio-button .mdc-radio__native-control:disabled + .mdc-radio__background > .mdc-radio__outer-circle {
  border-color: var(--mat-radio-disabled-selected-icon-color, var(--mat-sys-on-surface));
  opacity: var(--mat-radio-disabled-selected-icon-opacity, 0.38);
}
.mat-mdc-radio-button .mdc-radio__native-control:disabled + .mdc-radio__background > .mdc-radio__inner-circle {
  background-color: var(--mat-radio-disabled-selected-icon-color, var(--mat-sys-on-surface, currentColor));
  opacity: var(--mat-radio-disabled-selected-icon-opacity, 0.38);
}
.mat-mdc-radio-button .mdc-radio__native-control:enabled:not(:checked) + .mdc-radio__background > .mdc-radio__outer-circle {
  border-color: var(--mat-radio-unselected-icon-color, var(--mat-sys-on-surface-variant));
}
.mat-mdc-radio-button .mdc-radio__native-control:enabled:checked + .mdc-radio__background > .mdc-radio__outer-circle {
  border-color: var(--mat-radio-selected-icon-color, var(--mat-sys-primary));
}
.mat-mdc-radio-button .mdc-radio__native-control:enabled:checked + .mdc-radio__background > .mdc-radio__inner-circle {
  background-color: var(--mat-radio-selected-icon-color, var(--mat-sys-primary, currentColor));
}
.mat-mdc-radio-button .mdc-radio__native-control:enabled:focus:checked + .mdc-radio__background > .mdc-radio__outer-circle {
  border-color: var(--mat-radio-selected-focus-icon-color, var(--mat-sys-primary));
}
.mat-mdc-radio-button .mdc-radio__native-control:enabled:focus:checked + .mdc-radio__background > .mdc-radio__inner-circle {
  background-color: var(--mat-radio-selected-focus-icon-color, var(--mat-sys-primary, currentColor));
}
.mat-mdc-radio-button .mdc-radio__native-control:checked + .mdc-radio__background > .mdc-radio__inner-circle {
  transform: scale(0.5);
  transition: transform 90ms cubic-bezier(0, 0, 0.2, 1), background-color 90ms cubic-bezier(0, 0, 0.2, 1);
}
.mat-mdc-radio-button.mat-mdc-radio-disabled-interactive .mdc-radio--disabled {
  pointer-events: auto;
}
.mat-mdc-radio-button.mat-mdc-radio-disabled-interactive .mdc-radio--disabled .mdc-radio__native-control:not(:checked) + .mdc-radio__background > .mdc-radio__outer-circle {
  border-color: var(--mat-radio-disabled-unselected-icon-color, var(--mat-sys-on-surface));
  opacity: var(--mat-radio-disabled-unselected-icon-opacity, 0.38);
}
.mat-mdc-radio-button.mat-mdc-radio-disabled-interactive .mdc-radio--disabled:hover .mdc-radio__native-control:checked + .mdc-radio__background > .mdc-radio__outer-circle,
.mat-mdc-radio-button.mat-mdc-radio-disabled-interactive .mdc-radio--disabled .mdc-radio__native-control:checked:focus + .mdc-radio__background > .mdc-radio__outer-circle,
.mat-mdc-radio-button.mat-mdc-radio-disabled-interactive .mdc-radio--disabled .mdc-radio__native-control + .mdc-radio__background > .mdc-radio__outer-circle {
  border-color: var(--mat-radio-disabled-selected-icon-color, var(--mat-sys-on-surface));
  opacity: var(--mat-radio-disabled-selected-icon-opacity, 0.38);
}
.mat-mdc-radio-button.mat-mdc-radio-disabled-interactive .mdc-radio--disabled:hover .mdc-radio__native-control:checked + .mdc-radio__background > .mdc-radio__inner-circle,
.mat-mdc-radio-button.mat-mdc-radio-disabled-interactive .mdc-radio--disabled .mdc-radio__native-control:checked:focus + .mdc-radio__background > .mdc-radio__inner-circle,
.mat-mdc-radio-button.mat-mdc-radio-disabled-interactive .mdc-radio--disabled .mdc-radio__native-control + .mdc-radio__background > .mdc-radio__inner-circle {
  background-color: var(--mat-radio-disabled-selected-icon-color, var(--mat-sys-on-surface, currentColor));
  opacity: var(--mat-radio-disabled-selected-icon-opacity, 0.38);
}
.mat-mdc-radio-button._mat-animation-noopable .mdc-radio__background::before,
.mat-mdc-radio-button._mat-animation-noopable .mdc-radio__outer-circle,
.mat-mdc-radio-button._mat-animation-noopable .mdc-radio__inner-circle {
  transition: none !important;
}
.mat-mdc-radio-button label {
  cursor: pointer;
}
.mat-mdc-radio-button label:empty {
  display: none;
}
.mat-mdc-radio-button .mdc-radio__background::before {
  background-color: var(--mat-radio-ripple-color, var(--mat-sys-on-surface));
}
.mat-mdc-radio-button.mat-mdc-radio-checked .mat-ripple-element,
.mat-mdc-radio-button.mat-mdc-radio-checked .mdc-radio__background::before {
  background-color: var(--mat-radio-checked-ripple-color, var(--mat-sys-primary));
}
.mat-mdc-radio-button.mat-mdc-radio-disabled-interactive .mdc-radio--disabled .mat-ripple-element,
.mat-mdc-radio-button.mat-mdc-radio-disabled-interactive .mdc-radio--disabled .mdc-radio__background::before {
  background-color: var(--mat-radio-ripple-color, var(--mat-sys-on-surface));
}
.mat-mdc-radio-button .mat-internal-form-field {
  color: var(--mat-radio-label-text-color, var(--mat-sys-on-surface));
  font-family: var(--mat-radio-label-text-font, var(--mat-sys-body-medium-font));
  line-height: var(--mat-radio-label-text-line-height, var(--mat-sys-body-medium-line-height));
  font-size: var(--mat-radio-label-text-size, var(--mat-sys-body-medium-size));
  letter-spacing: var(--mat-radio-label-text-tracking, var(--mat-sys-body-medium-tracking));
  font-weight: var(--mat-radio-label-text-weight, var(--mat-sys-body-medium-weight));
}
.mat-mdc-radio-button .mdc-radio--disabled + label {
  color: var(--mat-radio-disabled-label-color, color-mix(in srgb, var(--mat-sys-on-surface) 38%, transparent));
}
.mat-mdc-radio-button .mat-radio-ripple {
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  position: absolute;
  pointer-events: none;
  border-radius: 50%;
}
.mat-mdc-radio-button .mat-radio-ripple > .mat-ripple-element {
  opacity: 0.14;
}
.mat-mdc-radio-button .mat-radio-ripple::before {
  border-radius: 50%;
}
.mat-mdc-radio-button .mdc-radio > .mdc-radio__native-control:focus:enabled:not(:checked) ~ .mdc-radio__background > .mdc-radio__outer-circle {
  border-color: var(--mat-radio-unselected-focus-icon-color, var(--mat-sys-on-surface));
}
.mat-mdc-radio-button.cdk-focused .mat-focus-indicator::before {
  content: "";
}

.mat-mdc-radio-disabled {
  cursor: default;
  pointer-events: none;
}
.mat-mdc-radio-disabled.mat-mdc-radio-disabled-interactive {
  pointer-events: auto;
}

.mat-mdc-radio-touch-target {
  position: absolute;
  top: 50%;
  left: 50%;
  height: var(--mat-radio-touch-target-size, 48px);
  width: var(--mat-radio-touch-target-size, 48px);
  transform: translate(-50%, -50%);
  display: var(--mat-radio-touch-target-display, block);
}
[dir=rtl] .mat-mdc-radio-touch-target {
  left: auto;
  right: 50%;
  transform: translate(50%, -50%);
}
`],encapsulation:2})}return i})();var Ut=["*"];function br(i,n){i&1&&eD(0);}var _r=["tabListContainer"],gr=["tabList"],vr=["tabListInner"],yr=["nextPaginator"],xr=["previousPaginator"],Cr=["content"];function Dr(i,n){}var wr=["tabBodyWrapper"],kr=["tabHeader"];function Mr(i,n){}function Er(i,n){if(i&1&&xp(0,Mr,0,0,"ng-template",12),i&2){let e=KE().$implicit;Fp("cdkPortalOutlet",e.templateLabel);}}function Fr(i,n){if(i&1&&MD(0),i&2){let e=KE().$implicit;sh(e.textLabel);}}function Tr(i,n){if(i&1){let e=$E();hi$1(0,"div",7,2),qp("click",function(){let a=Tu(e),r=a.$implicit,o=a.$index,p=KE(),C=iD(1);return Cu(p._handleClick(r,C,o))})("cdkFocusChange",function(a){let r=Tu(e).$index,o=KE();return Cu(o._tabFocusChanged(a,r))}),jp(2,"span",8)(3,"div",9),hi$1(4,"span",10)(5,"span",11),AE(6,Er,1,1,null,12)(7,Fr,1,1),kc()()();}if(i&2){let e=n.$implicit,t=n.$index,a=iD(1),r=KE();mD(e.labelClass),Xp("mdc-tab--active",r.selectedIndex===t),Fp("id",r._getTabLabelId(e,t))("disabled",e.disabled)("fitInkBarToContent",r.fitInkBarToContent),Lp("tabIndex",r._getTabIndex(t))("aria-posinset",t+1)("aria-setsize",r._tabs.length)("aria-controls",r._getTabContentId(t))("aria-selected",r.selectedIndex===t)("aria-label",e.ariaLabel||null)("aria-labelledby",!e.ariaLabel&&e.ariaLabelledby?e.ariaLabelledby:null),_v(3),Fp("matRippleTrigger",a)("matRippleDisabled",e.disabled||r.disableRipple),_v(3),RE(e.templateLabel?6:7);}}function Ar(i,n){i&1&&eD(0);}function Ir(i,n){if(i&1){let e=$E();hi$1(0,"mat-tab-body",13),qp("_onCentered",function(){Tu(e);let a=KE();return Cu(a._removeTabBodyWrapperHeight())})("_onCentering",function(a){Tu(e);let r=KE();return Cu(r._setTabBodyWrapperHeight(a))})("_beforeCentering",function(a){Tu(e);let r=KE();return Cu(r._bodyCentered(a))}),kc();}if(i&2){let e=n.$implicit,t=n.$index,a=KE();mD(e.bodyClass),Fp("id",a._getTabContentId(t))("content",e.content)("position",e.position)("animationDuration",a._bodyAnimationDuration)("preserveContent",a.preserveContent),Lp("tabindex",a.contentTabIndex!=null&&a.selectedIndex===t?a.contentTabIndex:null)("aria-labelledby",a._getTabLabelId(e,t))("aria-hidden",a.selectedIndex!==t);}}var Sr=new N("MatTabContent"),Vr=(()=>{class i{template=T(dr$1);static \u0275fac=function(t){return new(t||i)};static \u0275dir=dE({type:i,selectors:[["","matTabContent",""]],features:[PD([{provide:Sr,useExisting:i}])]})}return i})(),Rr=new N("MatTabLabel"),Ii=new N("MAT_TAB"),Pr=(()=>{class i extends _t{_closestTab=T(Ii,{optional:true});static \u0275fac=(()=>{let e;return function(a){return (e||(e=zm(i)))(a||i)}})();static \u0275dir=dE({type:i,selectors:[["","mat-tab-label",""],["","matTabLabel",""]],features:[PD([{provide:Rr,useExisting:i}]),Sp]})}return i})(),Si=new N("MAT_TAB_GROUP"),Or=(()=>{class i{_viewContainerRef=T(Ni);_closestTabGroup=T(Si,{optional:true});disabled=false;get templateLabel(){return this._templateLabel}set templateLabel(e){this._setTemplateLabelInput(e);}_templateLabel;_explicitContent=void 0;_implicitContent;textLabel="";ariaLabel;ariaLabelledby;labelClass;bodyClass;id=null;_contentPortal=null;get content(){return this._contentPortal}_stateChanges=new ee;position=null;origin=null;isActive=false;constructor(){T(F).load(oo);}ngOnChanges(e){(e.hasOwnProperty("textLabel")||e.hasOwnProperty("disabled"))&&this._stateChanges.next();}ngOnDestroy(){this._stateChanges.complete();}ngOnInit(){this._contentPortal=new E(this._explicitContent||this._implicitContent,this._viewContainerRef);}_setTemplateLabelInput(e){e&&e._closestTab===this&&(this._templateLabel=e);}static \u0275fac=function(t){return new(t||i)};static \u0275cmp=sE({type:i,selectors:[["mat-tab"]],contentQueries:function(t,a,r){if(t&1&&Gp(r,Pr,5)(r,Vr,7,dr$1),t&2){let o;nD(o=rD())&&(a.templateLabel=o.first),nD(o=rD())&&(a._explicitContent=o.first);}},viewQuery:function(t,a){if(t&1&&zp(dr$1,7),t&2){let r;nD(r=rD())&&(a._implicitContent=r.first);}},hostAttrs:["hidden",""],hostVars:1,hostBindings:function(t,a){t&2&&Lp("id",null);},inputs:{disabled:[2,"disabled","disabled",JF],textLabel:[0,"label","textLabel"],ariaLabel:[0,"aria-label","ariaLabel"],ariaLabelledby:[0,"aria-labelledby","ariaLabelledby"],labelClass:"labelClass",bodyClass:"bodyClass",id:"id"},exportAs:["matTab"],features:[PD([{provide:Ii,useExisting:i}]),Sm],ngContentSelectors:Ut,decls:1,vars:0,template:function(t,a){t&1&&(XE(),Ap(0,br,1,0,"ng-template"));},encapsulation:2,changeDetection:1})}return i})(),jt="mdc-tab-indicator--active",Fi="mdc-tab-indicator--no-transition",Gt=class{_items;_currentItem;constructor(n){this._items=n;}hide(){this._items.forEach(n=>n.deactivateInkBar()),this._currentItem=void 0;}alignToElement(n){let e=this._items.find(a=>a.elementRef.nativeElement===n),t=this._currentItem;if(e!==t&&(t?.deactivateInkBar(),e)){let a=t?.elementRef.nativeElement.getBoundingClientRect?.();e.activateInkBar(a),this._currentItem=e;}}},Nr=(()=>{class i{_elementRef=T(vr$1);_inkBarElement=null;_inkBarContentElement=null;_fitToContent=false;get fitInkBarToContent(){return this._fitToContent}set fitInkBarToContent(e){this._fitToContent!==e&&(this._fitToContent=e,this._inkBarElement&&this._appendInkBarElement());}activateInkBar(e){let t=this._elementRef.nativeElement;if(!e||!t.getBoundingClientRect||!this._inkBarContentElement){t.classList.add(jt);return}let a=t.getBoundingClientRect(),r=e.width/a.width,o=e.left-a.left;t.classList.add(Fi),this._inkBarContentElement.style.setProperty("transform",`translateX(${o}px) scaleX(${r})`),t.getBoundingClientRect(),t.classList.remove(Fi),t.classList.add(jt),this._inkBarContentElement.style.setProperty("transform","");}deactivateInkBar(){this._elementRef.nativeElement.classList.remove(jt);}ngOnInit(){this._createInkBarElement();}ngOnDestroy(){this._inkBarElement?.remove(),this._inkBarElement=this._inkBarContentElement=null;}_createInkBarElement(){let e=this._elementRef.nativeElement.ownerDocument||document,t=this._inkBarElement=e.createElement("span"),a=this._inkBarContentElement=e.createElement("span");t.className="mdc-tab-indicator",a.className="mdc-tab-indicator__content mdc-tab-indicator__content--underline",t.appendChild(this._inkBarContentElement),this._appendInkBarElement();}_appendInkBarElement(){this._inkBarElement;let e=this._fitToContent?this._elementRef.nativeElement.querySelector(".mdc-tab__content"):this._elementRef.nativeElement;e.appendChild(this._inkBarElement);}static \u0275fac=function(t){return new(t||i)};static \u0275dir=dE({type:i,inputs:{fitInkBarToContent:[2,"fitInkBarToContent","fitInkBarToContent",JF]}})}return i})();var Vi=(()=>{class i extends Nr{elementRef=T(vr$1);disabled=false;focus(){this.elementRef.nativeElement.focus();}getOffsetLeft(){return this.elementRef.nativeElement.offsetLeft}getOffsetWidth(){return this.elementRef.nativeElement.offsetWidth}static \u0275fac=(()=>{let e;return function(a){return (e||(e=zm(i)))(a||i)}})();static \u0275dir=dE({type:i,selectors:[["","matTabLabelWrapper",""]],hostVars:3,hostBindings:function(t,a){t&2&&(Lp("aria-disabled",!!a.disabled),Xp("mat-mdc-tab-disabled",a.disabled));},inputs:{disabled:[2,"disabled","disabled",JF]},features:[Sp]})}return i})(),Ti={passive:true},Lr=650,Br=100;function Ht(i){let n=i+"";return /^[0-9]+(?:\.[0-9]+)?$/.test(n)?`${i}ms`:/^[0-9]+(?:\.[0-9]+)?(?:ms|s)$/.test(n)?n:""}var zr=(()=>{class i{_elementRef=T(vr$1);_changeDetectorRef=T(YF);_viewportRuler=T(st$1);_dir=T(Er$1,{optional:true});_ngZone=T(_e);_platform=T(_);_sharedResizeObserver=T(st);_injector=T(pe);_renderer=T(lI);_animationsDisabled=nt$1();_eventCleanups;_scrollDistance=0;_selectedIndexChanged=false;_destroyed=new ee;_showPaginationControls=false;_disableScrollAfter=true;_disableScrollBefore=true;_tabLabelCount;_scrollDistanceChanged=false;_keyManager;_currentTextContent;_stopScrolling=new ee;disablePagination=false;get selectedIndex(){return this._selectedIndex}set selectedIndex(e){let t=isNaN(e)?0:e;this._selectedIndex!=t&&(this._selectedIndexChanged=true,this._selectedIndex=t,this._keyManager&&this._keyManager.updateActiveItem(t));}_selectedIndex=0;selectFocusedIndex=new He;indexFocused=new He;constructor(){this._eventCleanups=this._ngZone.runOutsideAngular(()=>[this._renderer.listen(this._elementRef.nativeElement,"mouseleave",()=>this._stopInterval())]);}ngAfterViewInit(){this._eventCleanups.push(this._renderer.listen(this._previousPaginator.nativeElement,"touchstart",()=>this._handlePaginatorPress("before"),Ti),this._renderer.listen(this._nextPaginator.nativeElement,"touchstart",()=>this._handlePaginatorPress("after"),Ti));}ngAfterContentInit(){let e=this._dir?this._dir.change:Bh("ltr"),t=this._sharedResizeObserver.observe(this._elementRef.nativeElement).pipe(vl(32),Dg(this._destroyed)),a=this._viewportRuler.change(150).pipe(Dg(this._destroyed)),r=()=>{this.updatePagination(),this._alignInkBarToSelectedTab();};this._keyManager=new _e$1(this._items).withHorizontalOrientation(this._getLayoutDirection()).withHomeAndEnd().withWrap().skipPredicate(()=>false),this._keyManager.updateActiveItem(Math.max(this._selectedIndex,0)),nv(r,{injector:this._injector}),ug(e,a,t,this._items.changes,this._itemsResized()).pipe(Dg(this._destroyed)).subscribe(()=>{this._ngZone.run(()=>{Promise.resolve().then(()=>{this._scrollDistance=Math.max(0,Math.min(this._getMaxScrollDistance(),this._scrollDistance)),r();});}),this._keyManager?.withHorizontalOrientation(this._getLayoutDirection());}),this._keyManager.change.subscribe(o=>{this.indexFocused.emit(o),this._setTabFocus(o);});}_itemsResized(){return typeof ResizeObserver!="function"?It$1:this._items.changes.pipe(Eg(this._items),El(e=>new M(t=>this._ngZone.runOutsideAngular(()=>{let a=new ResizeObserver(r=>t.next(r));return e.forEach(r=>a.observe(r.elementRef.nativeElement)),()=>{a.disconnect();}}))),Ig(1),Gt$1(e=>e.some(t=>t.contentRect.width>0&&t.contentRect.height>0)))}ngAfterContentChecked(){this._tabLabelCount!=this._items.length&&(this.updatePagination(),this._tabLabelCount=this._items.length,this._changeDetectorRef.markForCheck()),this._selectedIndexChanged&&(this._scrollToLabel(this._selectedIndex),this._checkScrollingControls(),this._alignInkBarToSelectedTab(),this._selectedIndexChanged=false,this._changeDetectorRef.markForCheck()),this._scrollDistanceChanged&&(this._updateTabScrollPosition(),this._scrollDistanceChanged=false,this._changeDetectorRef.markForCheck());}ngOnDestroy(){this._eventCleanups.forEach(e=>e()),this._keyManager?.destroy(),this._destroyed.next(),this._destroyed.complete(),this._stopScrolling.complete();}_handleKeydown(e){if(!Kn$1(e))switch(e.keyCode){case 13:case 32:if(this.focusIndex!==this.selectedIndex){let t=this._items.get(this.focusIndex);t&&!t.disabled&&(this.selectFocusedIndex.emit(this.focusIndex),this._itemSelected(e));}break;default:this._keyManager?.onKeydown(e);}}_onContentChanges(){let e=this._elementRef.nativeElement.textContent;e!==this._currentTextContent&&(this._currentTextContent=e||"",this._ngZone.run(()=>{this.updatePagination(),this._alignInkBarToSelectedTab(),this._changeDetectorRef.markForCheck();}));}updatePagination(){this._checkPaginationEnabled(),this._checkScrollingControls(),this._updateTabScrollPosition();}get focusIndex(){return this._keyManager?this._keyManager.activeItemIndex:0}set focusIndex(e){!this._isValidIndex(e)||this.focusIndex===e||!this._keyManager||this._keyManager.setActiveItem(e);}_isValidIndex(e){return this._items?!!this._items.toArray()[e]:true}_setTabFocus(e){if(this._showPaginationControls&&this._scrollToLabel(e),this._items&&this._items.length){this._items.toArray()[e].focus();let t=this._tabListContainer.nativeElement;this._getLayoutDirection()=="ltr"?t.scrollLeft=0:t.scrollLeft=t.scrollWidth-t.offsetWidth;}}_getLayoutDirection(){return this._dir&&this._dir.value==="rtl"?"rtl":"ltr"}_updateTabScrollPosition(){if(this.disablePagination)return;let e=this.scrollDistance,t=this._getLayoutDirection()==="ltr"?-e:e;this._tabList.nativeElement.style.transform=`translateX(${Math.round(t)}px)`,(this._platform.TRIDENT||this._platform.EDGE)&&(this._tabListContainer.nativeElement.scrollLeft=0);}get scrollDistance(){return this._scrollDistance}set scrollDistance(e){this._scrollTo(e);}_scrollHeader(e){let t=this._tabListContainer.nativeElement.offsetWidth,a=(e=="before"?-1:1)*t/3;return this._scrollTo(this._scrollDistance+a)}_handlePaginatorClick(e){this._stopInterval(),this._scrollHeader(e);}_scrollToLabel(e){if(this.disablePagination)return;let t=this._items?this._items.toArray()[e]:null;if(!t)return;let a=this._tabListContainer.nativeElement.offsetWidth,{offsetLeft:r,offsetWidth:o}=t.elementRef.nativeElement,p,C;this._getLayoutDirection()=="ltr"?(p=r,C=p+o):(C=this._tabListInner.nativeElement.offsetWidth-r,p=C-o);let U=this.scrollDistance,Y=this.scrollDistance+a;p<U?this.scrollDistance-=U-p:C>Y&&(this.scrollDistance+=Math.min(C-Y,p-U));}_checkPaginationEnabled(){if(this.disablePagination)this._showPaginationControls=false;else {let e=this._tabListInner.nativeElement.scrollWidth,t=this._elementRef.nativeElement.offsetWidth,a=e-t>=5;a||(this.scrollDistance=0),a!==this._showPaginationControls&&(this._showPaginationControls=a,this._changeDetectorRef.markForCheck());}}_checkScrollingControls(){this.disablePagination?this._disableScrollAfter=this._disableScrollBefore=true:(this._disableScrollBefore=this.scrollDistance==0,this._disableScrollAfter=this.scrollDistance==this._getMaxScrollDistance(),this._changeDetectorRef.markForCheck());}_getMaxScrollDistance(){let e=this._tabListInner.nativeElement.scrollWidth,t=this._tabListContainer.nativeElement.offsetWidth;return e-t||0}_alignInkBarToSelectedTab(){let e=this._items&&this._items.length?this._items.toArray()[this.selectedIndex]:null,t=e?e.elementRef.nativeElement:null;t?this._inkBar.alignToElement(t):this._inkBar.hide();}_stopInterval(){this._stopScrolling.next();}_handlePaginatorPress(e,t){t&&t.button!=null&&t.button!==0||(this._stopInterval(),Pn$1(Lr,Br).pipe(Dg(ug(this._stopScrolling,this._destroyed))).subscribe(()=>{let{maxScrollDistance:a,distance:r}=this._scrollHeader(e);(r===0||r>=a)&&this._stopInterval();}));}_scrollTo(e){if(this.disablePagination)return {maxScrollDistance:0,distance:0};let t=this._getMaxScrollDistance();return this._scrollDistance=Math.max(0,Math.min(t,e)),this._scrollDistanceChanged=true,this._checkScrollingControls(),{maxScrollDistance:t,distance:this._scrollDistance}}static \u0275fac=function(t){return new(t||i)};static \u0275dir=dE({type:i,inputs:{disablePagination:[2,"disablePagination","disablePagination",JF],selectedIndex:[2,"selectedIndex","selectedIndex",XF]},outputs:{selectFocusedIndex:"selectFocusedIndex",indexFocused:"indexFocused"}})}return i})(),jr=(()=>{class i extends zr{_items;_tabListContainer;_tabList;_tabListInner;_nextPaginator;_previousPaginator;_inkBar;ariaLabel;ariaLabelledby;disableRipple=false;ngAfterContentInit(){this._inkBar=new Gt(this._items),super.ngAfterContentInit();}_itemSelected(e){e.preventDefault();}static \u0275fac=(()=>{let e;return function(a){return (e||(e=zm(i)))(a||i)}})();static \u0275cmp=sE({type:i,selectors:[["mat-tab-header"]],contentQueries:function(t,a,r){if(t&1&&Gp(r,Vi,4),t&2){let o;nD(o=rD())&&(a._items=o);}},viewQuery:function(t,a){if(t&1&&zp(_r,7)(gr,7)(vr,7)(yr,5)(xr,5),t&2){let r;nD(r=rD())&&(a._tabListContainer=r.first),nD(r=rD())&&(a._tabList=r.first),nD(r=rD())&&(a._tabListInner=r.first),nD(r=rD())&&(a._nextPaginator=r.first),nD(r=rD())&&(a._previousPaginator=r.first);}},hostAttrs:[1,"mat-mdc-tab-header"],hostVars:4,hostBindings:function(t,a){t&2&&Xp("mat-mdc-tab-header-pagination-controls-enabled",a._showPaginationControls)("mat-mdc-tab-header-rtl",a._getLayoutDirection()=="rtl");},inputs:{ariaLabel:[0,"aria-label","ariaLabel"],ariaLabelledby:[0,"aria-labelledby","ariaLabelledby"],disableRipple:[2,"disableRipple","disableRipple",JF]},features:[Sp],ngContentSelectors:Ut,decls:13,vars:10,consts:[["previousPaginator",""],["tabListContainer",""],["tabList",""],["tabListInner",""],["nextPaginator",""],["mat-ripple","",1,"mat-mdc-tab-header-pagination","mat-mdc-tab-header-pagination-before",3,"click","mousedown","touchend","matRippleDisabled"],[1,"mat-mdc-tab-header-pagination-chevron"],[1,"mat-mdc-tab-label-container",3,"keydown"],["role","tablist",1,"mat-mdc-tab-list",3,"cdkObserveContent"],[1,"mat-mdc-tab-labels"],["mat-ripple","",1,"mat-mdc-tab-header-pagination","mat-mdc-tab-header-pagination-after",3,"mousedown","click","touchend","matRippleDisabled"]],template:function(t,a){t&1&&(XE(),hi$1(0,"div",5,0),qp("click",function(){return a._handlePaginatorClick("before")})("mousedown",function(o){return a._handlePaginatorPress("before",o)})("touchend",function(){return a._stopInterval()}),jp(2,"div",6),kc(),hi$1(3,"div",7,1),qp("keydown",function(o){return a._handleKeydown(o)}),hi$1(5,"div",8,2),qp("cdkObserveContent",function(){return a._onContentChanges()}),hi$1(7,"div",9,3),eD(9),kc()()(),hi$1(10,"div",10,4),qp("mousedown",function(o){return a._handlePaginatorPress("after",o)})("click",function(){return a._handlePaginatorClick("after")})("touchend",function(){return a._stopInterval()}),jp(12,"div",6),kc()),t&2&&(Xp("mat-mdc-tab-header-pagination-disabled",a._disableScrollBefore),Fp("matRippleDisabled",a._disableScrollBefore||a.disableRipple),_v(3),Xp("_mat-animation-noopable",a._animationsDisabled),_v(2),Lp("aria-label",a.ariaLabel||null)("aria-labelledby",a.ariaLabelledby||null),_v(5),Xp("mat-mdc-tab-header-pagination-disabled",a._disableScrollAfter),Fp("matRippleDisabled",a._disableScrollAfter||a.disableRipple));},dependencies:[tc,hr],styles:[`.mat-mdc-tab-header {
  display: flex;
  overflow: hidden;
  position: relative;
  flex-shrink: 0;
}

.mdc-tab-indicator .mdc-tab-indicator__content {
  transition-duration: var(--mat-tab-header-animation-duration, 250ms);
}

.mat-mdc-tab-header-pagination {
  -webkit-user-select: none;
  user-select: none;
  position: relative;
  display: none;
  justify-content: center;
  align-items: center;
  min-width: 32px;
  cursor: pointer;
  z-index: 2;
  -webkit-tap-highlight-color: transparent;
  touch-action: none;
  box-sizing: content-box;
  outline: 0;
}
.mat-mdc-tab-header-pagination::-moz-focus-inner {
  border: 0;
}
.mat-mdc-tab-header-pagination .mat-ripple-element {
  opacity: 0.12;
  background-color: var(--mat-tab-inactive-ripple-color, var(--mat-sys-on-surface));
}
.mat-mdc-tab-header-pagination-controls-enabled .mat-mdc-tab-header-pagination {
  display: flex;
}

.mat-mdc-tab-header-pagination-before,
.mat-mdc-tab-header-rtl .mat-mdc-tab-header-pagination-after {
  padding-left: 4px;
}
.mat-mdc-tab-header-pagination-before .mat-mdc-tab-header-pagination-chevron,
.mat-mdc-tab-header-rtl .mat-mdc-tab-header-pagination-after .mat-mdc-tab-header-pagination-chevron {
  transform: rotate(-135deg);
}

.mat-mdc-tab-header-rtl .mat-mdc-tab-header-pagination-before,
.mat-mdc-tab-header-pagination-after {
  padding-right: 4px;
}
.mat-mdc-tab-header-rtl .mat-mdc-tab-header-pagination-before .mat-mdc-tab-header-pagination-chevron,
.mat-mdc-tab-header-pagination-after .mat-mdc-tab-header-pagination-chevron {
  transform: rotate(45deg);
}

.mat-mdc-tab-header-pagination-chevron {
  border-style: solid;
  border-width: 2px 2px 0 0;
  height: 8px;
  width: 8px;
  border-color: var(--mat-tab-pagination-icon-color, var(--mat-sys-on-surface));
}

.mat-mdc-tab-header-pagination-disabled {
  box-shadow: none;
  cursor: default;
  pointer-events: none;
}
.mat-mdc-tab-header-pagination-disabled .mat-mdc-tab-header-pagination-chevron {
  opacity: 0.4;
}

.mat-mdc-tab-list {
  flex-grow: 1;
  position: relative;
  transition: transform 500ms cubic-bezier(0.35, 0, 0.25, 1);
}
._mat-animation-noopable .mat-mdc-tab-list {
  transition: none;
}

.mat-mdc-tab-label-container {
  display: flex;
  flex-grow: 1;
  overflow: hidden;
  z-index: 1;
  border-bottom-style: solid;
  border-bottom-width: var(--mat-tab-divider-height, 1px);
  border-bottom-color: var(--mat-tab-divider-color, var(--mat-sys-surface-variant));
}
.mat-mdc-tab-group-inverted-header .mat-mdc-tab-label-container {
  border-bottom: none;
  border-top-style: solid;
  border-top-width: var(--mat-tab-divider-height, 1px);
  border-top-color: var(--mat-tab-divider-color, var(--mat-sys-surface-variant));
}

.mat-mdc-tab-labels {
  display: flex;
  flex: 1 0 auto;
}
[mat-align-tabs=center] > .mat-mdc-tab-header .mat-mdc-tab-labels {
  justify-content: center;
}
[mat-align-tabs=end] > .mat-mdc-tab-header .mat-mdc-tab-labels {
  justify-content: flex-end;
}
.cdk-drop-list .mat-mdc-tab-labels, .mat-mdc-tab-labels.cdk-drop-list {
  min-height: var(--mat-tab-container-height, 48px);
}

.mat-mdc-tab::before {
  margin: 5px;
}
@media (forced-colors: active) {
  .mat-mdc-tab[aria-disabled=true] {
    color: GrayText;
  }
}
`],encapsulation:2,changeDetection:1})}return i})(),Hr=new N("MAT_TABS_CONFIG"),Ai=(()=>{class i extends gt{_host=T(qt);_ngZone=T(_e);_centeringSub=j.EMPTY;_leavingSub=j.EMPTY;ngOnInit(){super.ngOnInit(),this._centeringSub=this._host._beforeCentering.pipe(Eg(this._host._isCenterPosition())).subscribe(e=>{this._host._content&&e&&!this.hasAttached()&&this._ngZone.run(()=>{Promise.resolve().then(),this.attach(this._host._content);});}),this._leavingSub=this._host._afterLeavingCenter.subscribe(()=>{this._host.preserveContent||this._ngZone.run(()=>this.detach());});}ngOnDestroy(){super.ngOnDestroy(),this._centeringSub.unsubscribe(),this._leavingSub.unsubscribe();}static \u0275fac=(()=>{let e;return function(a){return (e||(e=zm(i)))(a||i)}})();static \u0275dir=dE({type:i,selectors:[["","matTabBodyHost",""]],features:[Sp]})}return i})(),qt=(()=>{class i{_elementRef=T(vr$1);_dir=T(Er$1,{optional:true});_ngZone=T(_e);_injector=T(pe);_renderer=T(lI);_diAnimationsDisabled=nt$1();_eventCleanups;_initialized=false;_fallbackTimer;_positionIndex;_dirChangeSubscription=j.EMPTY;_position;_previousPosition;_onCentering=new He;_beforeCentering=new He;_afterLeavingCenter=new He;_onCentered=new He(true);_portalHost;_contentElement;_content;animationDuration="500ms";preserveContent=false;set position(e){this._positionIndex=e,this._computePositionAnimationState();}constructor(){if(this._dir){let e=T(YF);this._dirChangeSubscription=this._dir.change.subscribe(t=>{this._computePositionAnimationState(t),e.markForCheck();});}}ngOnInit(){this._bindTransitionEvents(),this._position==="center"&&(this._setActiveClass(true),nv(()=>this._onCentering.emit(this._elementRef.nativeElement.clientHeight),{injector:this._injector})),this._initialized=true;}ngOnDestroy(){clearTimeout(this._fallbackTimer),this._eventCleanups?.forEach(e=>e()),this._dirChangeSubscription.unsubscribe();}_bindTransitionEvents(){this._ngZone.runOutsideAngular(()=>{let e=this._elementRef.nativeElement,t=a=>{a.target===this._contentElement?.nativeElement&&(this._elementRef.nativeElement.classList.remove("mat-tab-body-animating"),a.type==="transitionend"&&this._transitionDone());};this._eventCleanups=[this._renderer.listen(e,"transitionstart",a=>{a.target===this._contentElement?.nativeElement&&(this._elementRef.nativeElement.classList.add("mat-tab-body-animating"),this._transitionStarted());}),this._renderer.listen(e,"transitionend",t),this._renderer.listen(e,"transitioncancel",t)];});}_transitionStarted(){clearTimeout(this._fallbackTimer);let e=this._position==="center";this._beforeCentering.emit(e),e&&this._onCentering.emit(this._elementRef.nativeElement.clientHeight);}_transitionDone(){this._position==="center"?this._onCentered.emit():this._previousPosition==="center"&&this._afterLeavingCenter.emit();}_setActiveClass(e){this._elementRef.nativeElement.classList.toggle("mat-mdc-tab-body-active",e);}_getLayoutDirection(){return this._dir&&this._dir.value==="rtl"?"rtl":"ltr"}_isCenterPosition(){return this._positionIndex===0}_computePositionAnimationState(e=this._getLayoutDirection()){this._previousPosition=this._position,this._positionIndex<0?this._position=e=="ltr"?"left":"right":this._positionIndex>0?this._position=e=="ltr"?"right":"left":this._position="center",this._animationsDisabled()?this._simulateTransitionEvents():this._initialized&&(this._position==="center"||this._previousPosition==="center")&&(clearTimeout(this._fallbackTimer),this._fallbackTimer=this._ngZone.runOutsideAngular(()=>setTimeout(()=>this._simulateTransitionEvents(),100)));}_simulateTransitionEvents(){this._transitionStarted(),nv(()=>this._transitionDone(),{injector:this._injector});}_animationsDisabled(){return this._diAnimationsDisabled||this.animationDuration==="0ms"||this.animationDuration==="0s"}static \u0275fac=function(t){return new(t||i)};static \u0275cmp=sE({type:i,selectors:[["mat-tab-body"]],viewQuery:function(t,a){if(t&1&&zp(Ai,5)(Cr,5),t&2){let r;nD(r=rD())&&(a._portalHost=r.first),nD(r=rD())&&(a._contentElement=r.first);}},hostAttrs:[1,"mat-mdc-tab-body"],hostVars:1,hostBindings:function(t,a){t&2&&Lp("inert",a._position==="center"?null:"");},inputs:{_content:[0,"content","_content"],animationDuration:"animationDuration",preserveContent:"preserveContent",position:"position"},outputs:{_onCentering:"_onCentering",_beforeCentering:"_beforeCentering",_onCentered:"_onCentered"},decls:3,vars:6,consts:[["content",""],["cdkScrollable","",1,"mat-mdc-tab-body-content"],["matTabBodyHost",""]],template:function(t,a){t&1&&(hi$1(0,"div",1,0),xp(2,Dr,0,0,"ng-template",2),kc()),t&2&&Xp("mat-tab-body-content-left",a._position==="left")("mat-tab-body-content-right",a._position==="right")("mat-tab-body-content-can-animate",a._position==="center"||a._previousPosition==="center");},dependencies:[Ai,ot$1],styles:[`.mat-mdc-tab-body {
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  position: absolute;
  display: block;
  overflow: hidden;
  outline: 0;
  flex-basis: 100%;
}
.mat-mdc-tab-body.mat-mdc-tab-body-active {
  position: relative;
  overflow-x: hidden;
  overflow-y: auto;
  z-index: 1;
  flex-grow: 1;
}
.mat-mdc-tab-group.mat-mdc-tab-group-dynamic-height .mat-mdc-tab-body.mat-mdc-tab-body-active {
  overflow-y: hidden;
}

.mat-mdc-tab-body-content {
  height: 100%;
  overflow: auto;
  transform: none;
  visibility: hidden;
}
.mat-tab-body-animating > .mat-mdc-tab-body-content, .mat-mdc-tab-body-active > .mat-mdc-tab-body-content {
  visibility: visible;
}
.mat-tab-body-animating > .mat-mdc-tab-body-content {
  min-height: 1px;
}
.mat-mdc-tab-group-dynamic-height .mat-mdc-tab-body-content {
  overflow: hidden;
}

.mat-tab-body-content-can-animate {
  transition: transform var(--mat-tab-body-animation-duration) 1ms cubic-bezier(0.35, 0, 0.25, 1);
}
.mat-mdc-tab-body-wrapper._mat-animation-noopable .mat-tab-body-content-can-animate {
  transition: none;
}

.mat-tab-body-content-left {
  transform: translate3d(-100%, 0, 0);
}

.mat-tab-body-content-right {
  transform: translate3d(100%, 0, 0);
}
`],encapsulation:2,changeDetection:1})}return i})(),Qs=(()=>{class i{_elementRef=T(vr$1);_changeDetectorRef=T(YF);_ngZone=T(_e);_tabsSubscription=j.EMPTY;_tabLabelSubscription=j.EMPTY;_tabBodySubscription=j.EMPTY;_diAnimationsDisabled=nt$1();_bodyAnimationDuration;_headerAnimationDuration;_allTabs;_tabBodies;_tabBodyWrapper;_tabHeader;_tabs=new ei$1;_indexToSelect=0;_lastFocusedTabIndex=null;_tabBodyWrapperHeight=0;color;get fitInkBarToContent(){return this._fitInkBarToContent}set fitInkBarToContent(e){this._fitInkBarToContent=e,this._changeDetectorRef.markForCheck();}_fitInkBarToContent=false;stretchTabs=true;alignTabs=null;dynamicHeight=false;get selectedIndex(){return this._selectedIndex}set selectedIndex(e){this._indexToSelect=isNaN(e)?null:e;}_selectedIndex=null;headerPosition="above";get animationDuration(){return this._animationDuration}set animationDuration(e){this._animationDuration=e,e&&typeof e=="object"?(this._bodyAnimationDuration=Ht(e.body),this._headerAnimationDuration=Ht(e.header)):this._headerAnimationDuration=this._bodyAnimationDuration=Ht(e);}_animationDuration;get contentTabIndex(){return this._contentTabIndex}set contentTabIndex(e){this._contentTabIndex=isNaN(e)?null:e;}_contentTabIndex=null;disablePagination=false;disableRipple=false;preserveContent=false;get backgroundColor(){return this._backgroundColor}set backgroundColor(e){let t=this._elementRef.nativeElement.classList;t.remove("mat-tabs-with-background",`mat-background-${this.backgroundColor}`),e&&t.add("mat-tabs-with-background",`mat-background-${e}`),this._backgroundColor=e;}_backgroundColor;ariaLabel;ariaLabelledby;selectedIndexChange=new He;focusChange=new He;animationDone=new He;selectedTabChange=new He(true);_groupId;_isServer=!T(_).isBrowser;constructor(){let e=T(Hr,{optional:true});this._groupId=T(ye$1).getId("mat-tab-group-"),this.animationDuration=e&&e.animationDuration?e.animationDuration:"500ms",this.disablePagination=e&&e.disablePagination!=null?e.disablePagination:false,this.dynamicHeight=e&&e.dynamicHeight!=null?e.dynamicHeight:false,e?.contentTabIndex!=null&&(this.contentTabIndex=e.contentTabIndex),this.preserveContent=!!e?.preserveContent,this.fitInkBarToContent=e&&e.fitInkBarToContent!=null?e.fitInkBarToContent:false,this.stretchTabs=e&&e.stretchTabs!=null?e.stretchTabs:true,this.alignTabs=e&&e.alignTabs!=null?e.alignTabs:null;}ngAfterContentChecked(){let e=this._indexToSelect=this._clampTabIndex(this._indexToSelect);if(this._selectedIndex!=e){let t=this._selectedIndex==null;if(!t){this.selectedTabChange.emit(this._createChangeEvent(e));let a=this._tabBodyWrapper.nativeElement;a.style.minHeight=a.clientHeight+"px";}Promise.resolve().then(()=>{this._tabs.forEach((a,r)=>a.isActive=r===e),t||(this.selectedIndexChange.emit(e),this._tabBodyWrapper.nativeElement.style.minHeight="");});}this._tabs.forEach((t,a)=>{t.position=a-e,this._selectedIndex!=null&&t.position==0&&!t.origin&&(t.origin=e-this._selectedIndex);}),this._selectedIndex!==e&&(this._selectedIndex=e,this._lastFocusedTabIndex=null,this._changeDetectorRef.markForCheck());}ngAfterContentInit(){this._subscribeToAllTabChanges(),this._subscribeToTabLabels(),this._tabsSubscription=this._tabs.changes.subscribe(()=>{let e=this._clampTabIndex(this._indexToSelect);if(e===this._selectedIndex){let t=this._tabs.toArray(),a;for(let r=0;r<t.length;r++)if(t[r].isActive){this._indexToSelect=this._selectedIndex=r,this._lastFocusedTabIndex=null,a=t[r];break}!a&&t[e]&&Promise.resolve().then(()=>{t[e].isActive=true,this.selectedTabChange.emit(this._createChangeEvent(e));});}this._changeDetectorRef.markForCheck();});}ngAfterViewInit(){this._tabBodySubscription=this._tabBodies.changes.subscribe(()=>this._bodyCentered(true));}_subscribeToAllTabChanges(){this._allTabs.changes.pipe(Eg(this._allTabs)).subscribe(e=>{this._tabs.reset(e.filter(t=>t._closestTabGroup===this||!t._closestTabGroup)),this._tabs.notifyOnChanges();});}ngOnDestroy(){this._tabs.destroy(),this._tabsSubscription.unsubscribe(),this._tabLabelSubscription.unsubscribe(),this._tabBodySubscription.unsubscribe();}realignInkBar(){this._tabHeader&&this._tabHeader._alignInkBarToSelectedTab();}updatePagination(){this._tabHeader&&this._tabHeader.updatePagination();}focusTab(e){let t=this._tabHeader;t&&(t.focusIndex=e);}_focusChanged(e){this._lastFocusedTabIndex=e,this.focusChange.emit(this._createChangeEvent(e));}_createChangeEvent(e){let t=new Wt;return t.index=e,this._tabs&&this._tabs.length&&(t.tab=this._tabs.toArray()[e]),t}_subscribeToTabLabels(){this._tabLabelSubscription&&this._tabLabelSubscription.unsubscribe(),this._tabLabelSubscription=ug(...this._tabs.map(e=>e._stateChanges)).subscribe(()=>this._changeDetectorRef.markForCheck());}_clampTabIndex(e){return Math.min(this._tabs.length-1,Math.max(e||0,0))}_getTabLabelId(e,t){return e.id||`${this._groupId}-label-${t}`}_getTabContentId(e){return `${this._groupId}-content-${e}`}_setTabBodyWrapperHeight(e){if(!this.dynamicHeight||!this._tabBodyWrapperHeight){this._tabBodyWrapperHeight=e;return}let t=this._tabBodyWrapper.nativeElement;t.style.height=this._tabBodyWrapperHeight+"px",this._tabBodyWrapper.nativeElement.offsetHeight&&(t.style.height=e+"px");}_removeTabBodyWrapperHeight(){let e=this._tabBodyWrapper.nativeElement;this._tabBodyWrapperHeight=e.clientHeight,e.style.height="",this._ngZone.run(()=>this.animationDone.emit());}_handleClick(e,t,a){t.focusIndex=a,e.disabled||(this.selectedIndex=a);}_getTabIndex(e){let t=this._lastFocusedTabIndex??this.selectedIndex;return e===t?0:-1}_tabFocusChanged(e,t){e&&e!=="mouse"&&e!=="touch"&&(this._tabHeader.focusIndex=t);}_bodyCentered(e){e&&this._tabBodies?.forEach((t,a)=>t._setActiveClass(a===this._selectedIndex));}_bodyAnimationsDisabled(){return this._diAnimationsDisabled||this._bodyAnimationDuration==="0"||this._bodyAnimationDuration==="0ms"}static \u0275fac=function(t){return new(t||i)};static \u0275cmp=sE({type:i,selectors:[["mat-tab-group"]],contentQueries:function(t,a,r){if(t&1&&Gp(r,Or,5),t&2){let o;nD(o=rD())&&(a._allTabs=o);}},viewQuery:function(t,a){if(t&1&&zp(wr,5)(kr,5)(qt,5),t&2){let r;nD(r=rD())&&(a._tabBodyWrapper=r.first),nD(r=rD())&&(a._tabHeader=r.first),nD(r=rD())&&(a._tabBodies=r);}},hostAttrs:[1,"mat-mdc-tab-group"],hostVars:13,hostBindings:function(t,a){t&2&&(Lp("mat-align-tabs",a.alignTabs),mD("mat-"+(a.color||"primary")),Jp("--mat-tab-body-animation-duration",a._bodyAnimationDuration)("--mat-tab-header-animation-duration",a._headerAnimationDuration),Xp("mat-mdc-tab-group-dynamic-height",a.dynamicHeight)("mat-mdc-tab-group-inverted-header",a.headerPosition==="below")("mat-mdc-tab-group-stretch-tabs",a.stretchTabs));},inputs:{color:"color",fitInkBarToContent:[2,"fitInkBarToContent","fitInkBarToContent",JF],stretchTabs:[2,"mat-stretch-tabs","stretchTabs",JF],alignTabs:[0,"mat-align-tabs","alignTabs"],dynamicHeight:[2,"dynamicHeight","dynamicHeight",JF],selectedIndex:[2,"selectedIndex","selectedIndex",XF],headerPosition:"headerPosition",animationDuration:"animationDuration",contentTabIndex:[2,"contentTabIndex","contentTabIndex",XF],disablePagination:[2,"disablePagination","disablePagination",JF],disableRipple:[2,"disableRipple","disableRipple",JF],preserveContent:[2,"preserveContent","preserveContent",JF],backgroundColor:"backgroundColor",ariaLabel:[0,"aria-label","ariaLabel"],ariaLabelledby:[0,"aria-labelledby","ariaLabelledby"]},outputs:{selectedIndexChange:"selectedIndexChange",focusChange:"focusChange",animationDone:"animationDone",selectedTabChange:"selectedTabChange"},exportAs:["matTabGroup"],features:[PD([{provide:Si,useExisting:i}])],ngContentSelectors:Ut,decls:9,vars:8,consts:[["tabHeader",""],["tabBodyWrapper",""],["tabNode",""],[3,"indexFocused","selectFocusedIndex","selectedIndex","disableRipple","disablePagination","aria-label","aria-labelledby"],["role","tab","matTabLabelWrapper","","cdkMonitorElementFocus","",1,"mdc-tab","mat-mdc-tab","mat-focus-indicator",3,"id","mdc-tab--active","class","disabled","fitInkBarToContent"],[1,"mat-mdc-tab-body-wrapper"],["role","tabpanel",3,"id","class","content","position","animationDuration","preserveContent"],["role","tab","matTabLabelWrapper","","cdkMonitorElementFocus","",1,"mdc-tab","mat-mdc-tab","mat-focus-indicator",3,"click","cdkFocusChange","id","disabled","fitInkBarToContent"],[1,"mdc-tab__ripple"],["mat-ripple","",1,"mat-mdc-tab-ripple",3,"matRippleTrigger","matRippleDisabled"],[1,"mdc-tab__content"],[1,"mdc-tab__text-label"],[3,"cdkPortalOutlet"],["role","tabpanel",3,"_onCentered","_onCentering","_beforeCentering","id","content","position","animationDuration","preserveContent"]],template:function(t,a){t&1&&(XE(),hi$1(0,"mat-tab-header",3,0),qp("indexFocused",function(o){return a._focusChanged(o)})("selectFocusedIndex",function(o){return a.selectedIndex=o}),PE(2,Tr,8,17,"div",4,OE),kc(),AE(4,Ar,1,0),hi$1(5,"div",5,1),PE(7,Ir,1,10,"mat-tab-body",6,OE),kc()),t&2&&(Fp("selectedIndex",a.selectedIndex||0)("disableRipple",a.disableRipple)("disablePagination",a.disablePagination),Pp("aria-label",a.ariaLabel)("aria-labelledby",a.ariaLabelledby),_v(2),LE(a._tabs),_v(2),RE(a._isServer?4:-1),_v(),Xp("_mat-animation-noopable",a._bodyAnimationsDisabled()),_v(2),LE(a._tabs));},dependencies:[jr,Vi,Co,tc,gt,qt],styles:[`.mdc-tab {
  min-width: 90px;
  padding: 0 24px;
  display: flex;
  flex: 1 0 auto;
  justify-content: center;
  box-sizing: border-box;
  border: none;
  outline: none;
  text-align: center;
  white-space: nowrap;
  cursor: pointer;
  z-index: 1;
  touch-action: manipulation;
}

.mdc-tab__content {
  display: flex;
  align-items: center;
  justify-content: center;
  height: inherit;
  pointer-events: none;
}

.mdc-tab__text-label {
  transition: 150ms color linear;
  display: inline-block;
  line-height: 1;
  z-index: 2;
}

.mdc-tab--active .mdc-tab__text-label {
  transition-delay: 100ms;
}

._mat-animation-noopable .mdc-tab__text-label {
  transition: none;
}

.mdc-tab-indicator {
  display: flex;
  position: absolute;
  top: 0;
  left: 0;
  justify-content: center;
  width: 100%;
  height: 100%;
  pointer-events: none;
  z-index: 1;
}

.mdc-tab-indicator__content {
  transition: var(--mat-tab-header-animation-duration, 250ms) transform cubic-bezier(0.4, 0, 0.2, 1);
  transform-origin: left;
  opacity: 0;
}

.mdc-tab-indicator__content--underline {
  align-self: flex-end;
  box-sizing: border-box;
  width: 100%;
  border-top-style: solid;
}

.mdc-tab-indicator--active .mdc-tab-indicator__content {
  opacity: 1;
}

._mat-animation-noopable .mdc-tab-indicator__content, .mdc-tab-indicator--no-transition .mdc-tab-indicator__content {
  transition: none;
}

.mat-mdc-tab-ripple.mat-mdc-tab-ripple {
  position: absolute;
  top: 0;
  left: 0;
  bottom: 0;
  right: 0;
  pointer-events: none;
}

.mat-mdc-tab {
  -webkit-tap-highlight-color: transparent;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-decoration: none;
  background: none;
  height: var(--mat-tab-container-height, 48px);
  font-family: var(--mat-tab-label-text-font, var(--mat-sys-title-small-font));
  font-size: var(--mat-tab-label-text-size, var(--mat-sys-title-small-size));
  letter-spacing: var(--mat-tab-label-text-tracking, var(--mat-sys-title-small-tracking));
  line-height: var(--mat-tab-label-text-line-height, var(--mat-sys-title-small-line-height));
  font-weight: var(--mat-tab-label-text-weight, var(--mat-sys-title-small-weight));
}
.mat-mdc-tab.mdc-tab {
  flex-grow: 0;
}
.mat-mdc-tab .mdc-tab-indicator__content--underline {
  border-color: var(--mat-tab-active-indicator-color, var(--mat-sys-primary));
  border-top-width: var(--mat-tab-active-indicator-height, 2px);
  border-radius: var(--mat-tab-active-indicator-shape, 0);
}
.mat-mdc-tab:hover .mdc-tab__text-label {
  color: var(--mat-tab-inactive-hover-label-text-color, var(--mat-sys-on-surface));
}
.mat-mdc-tab:focus .mdc-tab__text-label {
  color: var(--mat-tab-inactive-focus-label-text-color, var(--mat-sys-on-surface));
}
.mat-mdc-tab.mdc-tab--active .mdc-tab__text-label {
  color: var(--mat-tab-active-label-text-color, var(--mat-sys-on-surface));
}
.mat-mdc-tab.mdc-tab--active .mdc-tab__ripple::before,
.mat-mdc-tab.mdc-tab--active .mat-ripple-element {
  background-color: var(--mat-tab-active-ripple-color, var(--mat-sys-on-surface));
}
.mat-mdc-tab.mdc-tab--active:hover .mdc-tab__text-label {
  color: var(--mat-tab-active-hover-label-text-color, var(--mat-sys-on-surface));
}
.mat-mdc-tab.mdc-tab--active:hover .mdc-tab-indicator__content--underline {
  border-color: var(--mat-tab-active-hover-indicator-color, var(--mat-sys-primary));
}
.mat-mdc-tab.mdc-tab--active:focus .mdc-tab__text-label {
  color: var(--mat-tab-active-focus-label-text-color, var(--mat-sys-on-surface));
}
.mat-mdc-tab.mdc-tab--active:focus .mdc-tab-indicator__content--underline {
  border-color: var(--mat-tab-active-focus-indicator-color, var(--mat-sys-primary));
}
.mat-mdc-tab.mat-mdc-tab-disabled {
  opacity: 0.4;
  pointer-events: none;
}
.mat-mdc-tab.mat-mdc-tab-disabled .mdc-tab__content {
  pointer-events: none;
}
.mat-mdc-tab.mat-mdc-tab-disabled .mdc-tab__ripple::before,
.mat-mdc-tab.mat-mdc-tab-disabled .mat-ripple-element {
  background-color: var(--mat-tab-disabled-ripple-color, var(--mat-sys-on-surface-variant));
}
.mat-mdc-tab .mdc-tab__ripple::before {
  content: "";
  display: block;
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  opacity: 0;
  pointer-events: none;
  background-color: var(--mat-tab-inactive-ripple-color, var(--mat-sys-on-surface));
}
.mat-mdc-tab .mdc-tab__text-label {
  color: var(--mat-tab-inactive-label-text-color, var(--mat-sys-on-surface));
  display: inline-flex;
  align-items: center;
}
.mat-mdc-tab .mdc-tab__content {
  position: relative;
  pointer-events: auto;
}
.mat-mdc-tab:hover .mdc-tab__ripple::before {
  opacity: 0.04;
}
.mat-mdc-tab.cdk-program-focused .mdc-tab__ripple::before, .mat-mdc-tab.cdk-keyboard-focused .mdc-tab__ripple::before {
  opacity: 0.12;
}
.mat-mdc-tab .mat-ripple-element {
  opacity: 0.12;
  background-color: var(--mat-tab-inactive-ripple-color, var(--mat-sys-on-surface));
}
.mat-mdc-tab-group.mat-mdc-tab-group-stretch-tabs > .mat-mdc-tab-header .mat-mdc-tab {
  flex-grow: 1;
}

.mat-mdc-tab-group {
  display: flex;
  flex-direction: column;
  max-width: 100%;
}
.mat-mdc-tab-group.mat-tabs-with-background > .mat-mdc-tab-header, .mat-mdc-tab-group.mat-tabs-with-background > .mat-mdc-tab-header-pagination {
  background-color: var(--mat-tab-background-color);
}
.mat-mdc-tab-group.mat-tabs-with-background.mat-primary > .mat-mdc-tab-header .mat-mdc-tab .mdc-tab__text-label {
  color: var(--mat-tab-foreground-color);
}
.mat-mdc-tab-group.mat-tabs-with-background.mat-primary > .mat-mdc-tab-header .mdc-tab-indicator__content--underline {
  border-color: var(--mat-tab-foreground-color);
}
.mat-mdc-tab-group.mat-tabs-with-background:not(.mat-primary) > .mat-mdc-tab-header .mat-mdc-tab:not(.mdc-tab--active) .mdc-tab__text-label {
  color: var(--mat-tab-foreground-color);
}
.mat-mdc-tab-group.mat-tabs-with-background:not(.mat-primary) > .mat-mdc-tab-header .mat-mdc-tab:not(.mdc-tab--active) .mdc-tab-indicator__content--underline {
  border-color: var(--mat-tab-foreground-color);
}
.mat-mdc-tab-group.mat-tabs-with-background > .mat-mdc-tab-header .mat-mdc-tab-header-pagination-chevron,
.mat-mdc-tab-group.mat-tabs-with-background > .mat-mdc-tab-header .mat-focus-indicator::before, .mat-mdc-tab-group.mat-tabs-with-background > .mat-mdc-tab-header-pagination .mat-mdc-tab-header-pagination-chevron,
.mat-mdc-tab-group.mat-tabs-with-background > .mat-mdc-tab-header-pagination .mat-focus-indicator::before {
  border-color: var(--mat-tab-foreground-color);
}
.mat-mdc-tab-group.mat-tabs-with-background > .mat-mdc-tab-header .mat-ripple-element, .mat-mdc-tab-group.mat-tabs-with-background > .mat-mdc-tab-header .mdc-tab__ripple::before, .mat-mdc-tab-group.mat-tabs-with-background > .mat-mdc-tab-header-pagination .mat-ripple-element, .mat-mdc-tab-group.mat-tabs-with-background > .mat-mdc-tab-header-pagination .mdc-tab__ripple::before {
  background-color: var(--mat-tab-foreground-color);
}
.mat-mdc-tab-group.mat-tabs-with-background > .mat-mdc-tab-header .mat-mdc-tab-header-pagination-chevron, .mat-mdc-tab-group.mat-tabs-with-background > .mat-mdc-tab-header-pagination .mat-mdc-tab-header-pagination-chevron {
  color: var(--mat-tab-foreground-color);
}
.mat-mdc-tab-group.mat-mdc-tab-group-inverted-header {
  flex-direction: column-reverse;
}
.mat-mdc-tab-group.mat-mdc-tab-group-inverted-header .mdc-tab-indicator__content--underline {
  align-self: flex-start;
}

.mat-mdc-tab-body-wrapper {
  position: relative;
  overflow: hidden;
  display: flex;
  transition: height 500ms cubic-bezier(0.35, 0, 0.25, 1);
}
.mat-mdc-tab-body-wrapper._mat-animation-noopable {
  transition: none !important;
  animation: none !important;
}
`],encapsulation:2,changeDetection:1})}return i})(),Wt=class{index;tab};var Ys=(()=>{class i{isErrorState(e,t){return !!(e&&e.invalid&&(e.touched||t&&t.submitted))}static \u0275fac=function(t){return new(t||i)};static \u0275prov=yr$1({token:i,factory:i.\u0275fac})}return i})();var Ri=class{_defaultMatcher;ngControl;_parentFormGroup;_parentForm;_stateChanges;errorState=false;matcher;constructor(n,e,t,a,r){this._defaultMatcher=n,this.ngControl=e,this._parentFormGroup=t,this._parentForm=a,this._stateChanges=r;}updateErrorState(){let n=this.errorState,e=this._parentFormGroup||this._parentForm,t=this.matcher||this._defaultMatcher,a=this.ngControl?this.ngControl.control:null,r=t?.isErrorState(a,e)??false;r!==n&&(this.errorState=r,this._stateChanges.next());}};export{Bt as B,Ct as C,De as D,Ea as E,Ji as J,Ka as K,Lt as L,Mi as M,Nt as N,On as O,Qs as Q,Ri as R,Ya as Y,Z,Or as a,ur as b,Qe as c,Ce as d,aa as e,fr as f,fo as g,ho as h,tr as i,ae as j,ka as k,lo as l,mo as m,Ys as n,er as o,pa as p,ki as q,rr as r,so as s,ta as t,uo as u,va as v,li as w,xa as x,di as y,zt as z};