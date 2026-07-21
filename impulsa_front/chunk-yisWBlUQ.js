import {l as W,q,aX as Er$1,aY as x,aC as bE,aD as cu$1,aZ as SE,aG as Sr$1,aP as MI,aO as Dr$1,a_ as Hp$1,aJ as JD,n as nh$1,f as fh$1,aE as $e,a$ as fy,av as ge,aI as zm$1,b0 as AE,b1 as Xr,b2 as Br$1,b3 as zs$1,b4 as Wr,b5 as js$1,b6 as Mi$1,b7 as Ye,af as Tr$1,b8 as jr,T,b9 as L0,ba as D,bb as Y,aH as Se,R as R0,aa as gw,bc as O0,bd as mm$1,V as Vo$1,be as ne,bf as B,bg as Er$2,h as nd$1,bh as Hl$1,a7 as Ce,bi as Vg,bj as en,bk as Bl$1,bl as kg,bm as H0,a as TE,bn as jn$1,aT as gD,$ as $p,b as yi,Q as QE,aW as mD,j as jc$1,W as Wv,Z as ZE,m as ch$1,aL as sh$1,E as ED,aM as vD,aN as ID,bo as ah$1,bp as ih$1,bq as rm$1,br as nf,bs as $o$1,bt as Hr$1,aS as j0,bu as eh$1,ac as Qp,bv as le,bw as W$1,bx as bn$1,by as Ah$1,bz as V0,aw as Dv,bA as rr$1,bB as bf,Y as Yp,c as Zp,bC as Ai$1,bD as at$1,bE as Up,bF as hr$1,bG as j,bH as ri,bI as ba$1,bJ as st,J as JE,bK as zp$1,X as XE,as as RD,am as dh$1,bL as We,bM as tn,bN as mw,bO as ni,al as Cl$1,bP as $,bQ as or$1,bR as Oe,bS as Re,bT as On$1,S as sr$1,ae as Ss$1,U as UD,bU as Gu,bV as zu,d as Uc$1,bW as Xp,a5 as DD,aK as vo,bX as he,bY as Jn,bZ as Zm$1,b_ as Km$1,b$ as Cg,c0 as yi$1,c1 as bi$1,c2 as ei,c3 as H,c4 as as$1,c5 as Qa$1,c6 as Kd$1,c7 as cn,c8 as Ht,c9 as bt,ca as Le,cb as vi,cc as Zt,cd as Ba$1,aU as Vc$1,ce as Kp,aV as Hc$1,cf as et,cg as jo$1,ch as Dt,ci as bg,cj as pe,F as Fe,ck as Kt$1,cl as di,cm as ti,cn as jo$2,p as pD,co as gr$1,cp as K,cq as Qc$1,cr as mi$1,cs as Tr$2,ct as _a$1,ai as rg,aj as Ul$1,cu as ug,ak as Ki,cv as Vl$1,cw as es$1,cx as Me,o as an,a8 as Yn,s as re,cy as gt,cz as BI,i as iD,cA as _w,cB as Au,cC as kn$1,cD as lr$1,cE as Qm$1,cF as Ne,cG as dg,cH as wg,cI as Og,cJ as b,cK as qa$1,cL as gi$1,I as Ih$1,cM as rh$1,cN as ae,cO as Yt$1,cP as Ut$1,cQ as ci$1,cR as _,cS as jt,cT as Fl$1,cU as ft,cV as ar$1,cW as Pc$1,cX as Te,cY as Xe$1,cZ as ce,c_ as Ta$1,L as Lr$1,c$ as Ur,az as A0,d0 as uw,ao as QD,d1 as ui$1,d2 as k0,d3 as x0,e as ku,O as Ou,w as wl$1,d4 as Bg,d5 as He,ag as KE,d6 as lg,d7 as cg,d8 as nw,d9 as Hg,da as Th$1,db as wh$1,dc as ew,ab as Eh$1,dd as Pu,de as zD}from'./main-B4IEGQ4X.js';import {L}from'./chunk-BW-oZiS1.js';var zo=(()=>{class n{_renderer;_elementRef;onChange=e=>{};onTouched=()=>{};constructor(e,t){this._renderer=e,this._elementRef=t;}setProperty(e,t){this._renderer.setProperty(this._elementRef.nativeElement,e,t);}registerOnTouched(e){this.onTouched=e;}registerOnChange(e){this.onChange=e;}setDisabledState(e){this.setProperty("disabled",e);}static \u0275fac=function(t){return new(t||n)(Sr$1(MI),Sr$1(Dr$1))};static \u0275dir=SE({type:n})}return n})(),Ho=(()=>{class n extends zo{static \u0275fac=(()=>{let e;return function(i){return (e||(e=fy(n)))(i||n)}})();static \u0275dir=SE({type:n,features:[Hp$1]})}return n})(),Xe=new x("");var Pl={provide:Xe,useExisting:vo(()=>jo),multi:true};function Vl(){let n=pe()?pe().getUserAgent():"";return /android (\d+)/.test(n.toLowerCase())}var Ll=new x(""),jo=(()=>{class n extends zo{_compositionMode;_composing=false;constructor(e,t,i){super(e,t),this._compositionMode=i,this._compositionMode==null&&(this._compositionMode=!Vl());}writeValue(e){let t=e??"";this.setProperty("value",t);}_handleInput(e){(!this._compositionMode||this._compositionMode&&!this._composing)&&this.onChange(e);}_compositionStart(){this._composing=true;}_compositionEnd(e){this._composing=false,this._compositionMode&&this.onChange(e);}static \u0275fac=function(t){return new(t||n)(Sr$1(MI),Sr$1(Dr$1),Sr$1(Ll,8))};static \u0275dir=SE({type:n,selectors:[["input","formControlName","",3,"type","checkbox",3,"ngNoCva",""],["textarea","formControlName","",3,"ngNoCva",""],["input","formControl","",3,"type","checkbox",3,"ngNoCva",""],["textarea","formControl","",3,"ngNoCva",""],["input","ngModel","",3,"type","checkbox",3,"ngNoCva",""],["textarea","ngModel","",3,"ngNoCva",""],["","ngDefaultControl",""]],hostBindings:function(t,i){t&1&&nh$1("input",function(o){return i._handleInput(o.target.value)})("blur",function(){return i.onTouched()})("compositionstart",function(){return i._compositionStart()})("compositionend",function(o){return i._compositionEnd(o.target.value)});},standalone:false,features:[JD([Pl]),Hp$1]})}return n})();function _a(n){return n==null||fa(n)===0}function fa(n){return n==null?null:Array.isArray(n)||typeof n=="string"?n.length:n instanceof Set?n.size:null}var Ze=new x(""),ui=new x(""),Nl=/^(?=.{1,254}$)(?=.{1,64}@)[a-zA-Z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&'*+/=?^_`{|}~-]+)*@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/,Gt=class{static min(r){return Go(r)}static max(r){return Uo(r)}static required(r){return Wo(r)}static requiredTrue(r){return Bl(r)}static email(r){return zl(r)}static minLength(r){return Hl(r)}static maxLength(r){return qo(r)}static pattern(r){return jl(r)}static nullValidator(r){return _n()}static compose(r){return Zo(r)}static composeAsync(r){return Jo(r)}};function Go(n){return r=>{if(r.value==null||n==null)return null;let e=parseFloat(r.value);return !isNaN(e)&&e<n?{min:{min:n,actual:r.value}}:null}}function Uo(n){return r=>{if(r.value==null||n==null)return null;let e=parseFloat(r.value);return !isNaN(e)&&e>n?{max:{max:n,actual:r.value}}:null}}function Wo(n){return _a(n.value)?{required:true}:null}function Bl(n){return n.value===true?null:{required:true}}function zl(n){return _a(n.value)||Nl.test(n.value)?null:{email:true}}function Hl(n){return r=>{let e=r.value?.length??fa(r.value);return e===null||e===0?null:e<n?{minlength:{requiredLength:n,actualLength:e}}:null}}function qo(n){return r=>{let e=r.value?.length??fa(r.value);return e!==null&&e>n?{maxlength:{requiredLength:n,actualLength:e}}:null}}function jl(n){if(!n)return _n;let r,e;return typeof n=="string"?(e="",n.charAt(0)!=="^"&&(e+="^"),e+=n,n.charAt(n.length-1)!=="$"&&(e+="$"),r=new RegExp(e)):(e=n.toString(),r=n),t=>{if(_a(t.value))return null;let i=t.value;return r.test(i)?null:{pattern:{requiredPattern:e,actualValue:i}}}}function _n(n){return null}function Qo(n){return n!=null}function Yo(n){return Pc$1(n)?Te(n):n}function Ko(n){let r={};return n.forEach(e=>{r=e!=null?q(q({},r),e):r;}),Object.keys(r).length===0?null:r}function $o(n,r){return r.map(e=>e(n))}function Gl(n){return !n.validate}function Xo(n){return n.map(r=>Gl(r)?r:e=>r.validate(e))}function Zo(n){if(!n)return null;let r=n.filter(Qo);return r.length==0?null:function(e){return Ko($o(e,r))}}function ga(n){return n!=null?Zo(Xo(n)):null}function Jo(n){if(!n)return null;let r=n.filter(Qo);return r.length==0?null:function(e){let t=$o(e,r).map(Yo);return bg(t).pipe(Ce(Ko))}}function ba(n){return n!=null?Jo(Xo(n)):null}function Oo(n,r){return n===null?[r]:Array.isArray(n)?[...n,r]:[n,r]}function es(n){return n._rawValidators}function ts(n){return n._rawAsyncValidators}function ma(n){return n?Array.isArray(n)?n:[n]:[]}function fn(n,r){return Array.isArray(n)?n.includes(r):n===r}function Po(n,r){let e=ma(r);return ma(n).forEach(i=>{fn(e,i)||e.push(i);}),e}function Vo(n,r){return ma(r).filter(e=>!fn(n,e))}var gn=class{get value(){return this.control?this.control.value:null}get valid(){return this.control?this.control.valid:null}get invalid(){return this.control?this.control.invalid:null}get pending(){return this.control?this.control.pending:null}get disabled(){return this.control?this.control.disabled:null}get enabled(){return this.control?this.control.enabled:null}get errors(){return this.control?this.control.errors:null}get pristine(){return this.control?this.control.pristine:null}get dirty(){return this.control?this.control.dirty:null}get touched(){return this.control?this.control.touched:null}get status(){return this.control?this.control.status:null}get untouched(){return this.control?this.control.untouched:null}get statusChanges(){return this.control?this.control.statusChanges:null}get valueChanges(){return this.control?this.control.valueChanges:null}get path(){return null}_composedValidatorFn;_composedAsyncValidatorFn;_rawValidators=[];_rawAsyncValidators=[];_setValidators(r){this._rawValidators=r||[],this._composedValidatorFn=ga(this._rawValidators);}_setAsyncValidators(r){this._rawAsyncValidators=r||[],this._composedAsyncValidatorFn=ba(this._rawAsyncValidators);}get validator(){return this._composedValidatorFn||null}get asyncValidator(){return this._composedAsyncValidatorFn||null}_onDestroyCallbacks=[];_registerOnDestroy(r){this._onDestroyCallbacks.push(r);}_invokeOnDestroyCallbacks(){this._onDestroyCallbacks.forEach(r=>r()),this._onDestroyCallbacks=[];}reset(r=void 0){this.control?.reset(r);}hasError(r,e){return this.control?this.control.hasError(r,e):false}getError(r,e){return this.control?this.control.getError(r,e):null}},Ge=class extends gn{name;get formDirective(){return null}get path(){return null}};var Ci="VALID",pn="INVALID",ci="PENDING",Di="DISABLED",Ft=class{},bn=class extends Ft{value;source;constructor(r,e){super(),this.value=r,this.source=e;}},Si=class extends Ft{pristine;source;constructor(r,e){super(),this.pristine=r,this.source=e;}},Ii=class extends Ft{touched;source;constructor(r,e){super(),this.touched=r,this.source=e;}},mi=class extends Ft{status;source;constructor(r,e){super(),this.status=r,this.source=e;}},vn=class extends Ft{source;constructor(r){super(),this.source=r;}},Ut=class extends Ft{source;constructor(r){super(),this.source=r;}};function va(n){return (Cn(n)?n.validators:n)||null}function Ul(n){return Array.isArray(n)?ga(n):n||null}function ya(n,r){return (Cn(r)?r.asyncValidators:n)||null}function Wl(n){return Array.isArray(n)?ba(n):n||null}function Cn(n){return n!=null&&!Array.isArray(n)&&typeof n=="object"}function is(n,r,e){let t=n.controls;if(!(r?Object.keys(t):t).length)throw new b(1e3,"");if(!as(t,e))throw new b(1001,"")}function ns(n,r,e){n._forEachChild((t,i)=>{if(e[i]===void 0)throw new b(-1002,"")});}var hi=class{_pendingDirty=false;_hasOwnPendingAsyncValidator=null;_pendingTouched=false;_onCollectionChange=()=>{};_updateOn;_hasRequired=Vo$1(false);_parent=null;_asyncValidationSubscription;_composedValidatorFn;_composedAsyncValidatorFn;_rawValidators;_rawAsyncValidators;value;constructor(r,e){this._assignValidators(r),this._assignAsyncValidators(e);}get validator(){return this._composedValidatorFn}set validator(r){this._rawValidators=this._composedValidatorFn=r,this._updateHasRequiredValidator();}get asyncValidator(){return this._composedAsyncValidatorFn}set asyncValidator(r){this._rawAsyncValidators=this._composedAsyncValidatorFn=r;}get parent(){return this._parent}get status(){return mw(this.statusReactive)}set status(r){mw(()=>this.statusReactive.set(r));}_status=gw(()=>this.statusReactive());statusReactive=Vo$1(void 0);get valid(){return this.status===Ci}get invalid(){return this.status===pn}get pending(){return this.status===ci}get disabled(){return this.status===Di}get enabled(){return this.status!==Di}errors;get pristine(){return mw(this.pristineReactive)}set pristine(r){mw(()=>this.pristineReactive.set(r));}_pristine=gw(()=>this.pristineReactive());pristineReactive=Vo$1(true);get dirty(){return !this.pristine}get touched(){return mw(this.touchedReactive)}set touched(r){mw(()=>this.touchedReactive.set(r));}_touched=gw(()=>this.touchedReactive());touchedReactive=Vo$1(false);get untouched(){return !this.touched}_events=new ne;events=this._events.asObservable();valueChanges;statusChanges;get updateOn(){return this._updateOn?this._updateOn:this.parent?this.parent.updateOn:"change"}setValidators(r){this._assignValidators(r);}setAsyncValidators(r){this._assignAsyncValidators(r);}addValidators(r){this.setValidators(Po(r,this._rawValidators));}addAsyncValidators(r){this.setAsyncValidators(Po(r,this._rawAsyncValidators));}removeValidators(r){this.setValidators(Vo(r,this._rawValidators));}removeAsyncValidators(r){this.setAsyncValidators(Vo(r,this._rawAsyncValidators));}hasValidator(r){return fn(this._rawValidators,r)}hasAsyncValidator(r){return fn(this._rawAsyncValidators,r)}clearValidators(){this.validator=null;}clearAsyncValidators(){this.asyncValidator=null;}markAsTouched(r={}){let e=this.touched===false;this.touched=true;let t=r.sourceControl??this;r.onlySelf||this._parent?.markAsTouched(W(q({},r),{sourceControl:t})),e&&r.emitEvent!==false&&this._events.next(new Ii(true,t));}markAllAsDirty(r={}){this.markAsDirty({onlySelf:true,emitEvent:r.emitEvent,sourceControl:this}),this._forEachChild(e=>e.markAllAsDirty(r));}markAllAsTouched(r={}){this.markAsTouched({onlySelf:true,emitEvent:r.emitEvent,sourceControl:this}),this._forEachChild(e=>e.markAllAsTouched(r));}markAsUntouched(r={}){let e=this.touched===true;this.touched=false,this._pendingTouched=false;let t=r.sourceControl??this;this._forEachChild(i=>{i.markAsUntouched({onlySelf:true,emitEvent:r.emitEvent,sourceControl:t});}),r.onlySelf||this._parent?._updateTouched(r,t),e&&r.emitEvent!==false&&this._events.next(new Ii(false,t));}markAsDirty(r={}){let e=this.pristine===true;this.pristine=false;let t=r.sourceControl??this;r.onlySelf||this._parent?.markAsDirty(W(q({},r),{sourceControl:t})),e&&r.emitEvent!==false&&this._events.next(new Si(false,t));}markAsPristine(r={}){let e=this.pristine===false;this.pristine=true,this._pendingDirty=false;let t=r.sourceControl??this;this._forEachChild(i=>{i.markAsPristine({onlySelf:true,emitEvent:r.emitEvent});}),r.onlySelf||this._parent?._updatePristine(r,t),e&&r.emitEvent!==false&&this._events.next(new Si(true,t));}markAsPending(r={}){this.status=ci;let e=r.sourceControl??this;r.emitEvent!==false&&(this._events.next(new mi(this.status,e)),this.statusChanges.emit(this.status)),r.onlySelf||this._parent?.markAsPending(W(q({},r),{sourceControl:e}));}disable(r={}){let e=this._parentMarkedDirty(r.onlySelf);this.status=Di,this.errors=null,this._forEachChild(i=>{i.disable(W(q({},r),{onlySelf:true}));}),this._updateValue();let t=r.sourceControl??this;r.emitEvent!==false&&(this._events.next(new bn(this.value,t)),this._events.next(new mi(this.status,t)),this.valueChanges.emit(this.value),this.statusChanges.emit(this.status)),this._updateAncestors(W(q({},r),{skipPristineCheck:e}),this),this._onDisabledChange.forEach(i=>i(true));}enable(r={}){let e=this._parentMarkedDirty(r.onlySelf);this.status=Ci,this._forEachChild(t=>{t.enable(W(q({},r),{onlySelf:true}));}),this.updateValueAndValidity({onlySelf:true,emitEvent:r.emitEvent}),this._updateAncestors(W(q({},r),{skipPristineCheck:e}),this),this._onDisabledChange.forEach(t=>t(false));}_updateAncestors(r,e){r.onlySelf||(this._parent?.updateValueAndValidity(r),r.skipPristineCheck||this._parent?._updatePristine({},e),this._parent?._updateTouched({},e));}setParent(r){this._parent=r;}getRawValue(){return this.value}updateValueAndValidity(r={}){if(this._setInitialStatus(),this._updateValue(),this.enabled){let t=this._cancelExistingSubscription();this.errors=this._runValidator(),this.status=this._calculateStatus(),(this.status===Ci||this.status===ci)&&this._runAsyncValidator(t,r.emitEvent);}let e=r.sourceControl??this;r.emitEvent!==false&&(this._events.next(new bn(this.value,e)),this._events.next(new mi(this.status,e)),this.valueChanges.emit(this.value),this.statusChanges.emit(this.status)),r.onlySelf||this._parent?.updateValueAndValidity(W(q({},r),{sourceControl:e}));}_updateTreeValidity(r={emitEvent:true}){this._forEachChild(e=>e._updateTreeValidity(r)),this.updateValueAndValidity({onlySelf:true,emitEvent:r.emitEvent});}_setInitialStatus(){this.status=this._allControlsDisabled()?Di:Ci;}_runValidator(){return this.validator?this.validator(this):null}_runAsyncValidator(r,e){if(this.asyncValidator){this.status=ci,this._hasOwnPendingAsyncValidator={emitEvent:e!==false,shouldHaveEmitted:r!==false};let t=Yo(this.asyncValidator(this));this._asyncValidationSubscription=t.subscribe(i=>{this._hasOwnPendingAsyncValidator=null,this.setErrors(i,{emitEvent:e,shouldHaveEmitted:r});});}}_cancelExistingSubscription(){if(this._asyncValidationSubscription){this._asyncValidationSubscription.unsubscribe();let r=(this._hasOwnPendingAsyncValidator?.emitEvent||this._hasOwnPendingAsyncValidator?.shouldHaveEmitted)??false;return this._hasOwnPendingAsyncValidator=null,r}return  false}setErrors(r,e={}){this.errors=r,this._updateControlsErrors(e.emitEvent!==false,this,e.shouldHaveEmitted);}get(r){let e=r;return e==null||(Array.isArray(e)||(e=e.split(".")),e.length===0)?null:e.reduce((t,i)=>t&&t._find(i),this)}getError(r,e){let t=e?this.get(e):this;return t?.errors?t.errors[r]:null}hasError(r,e){return !!this.getError(r,e)}get root(){let r=this;for(;r._parent;)r=r._parent;return r}_updateControlsErrors(r,e,t){this.status=this._calculateStatus(),r&&this.statusChanges.emit(this.status),(r||t)&&this._events.next(new mi(this.status,e)),this._parent&&this._parent._updateControlsErrors(r,e,t);}_initObservables(){this.valueChanges=new $e,this.statusChanges=new $e;}_calculateStatus(){return this._allControlsDisabled()?Di:this.errors?pn:this._hasOwnPendingAsyncValidator||this._anyControlsHaveStatus(ci)?ci:this._anyControlsHaveStatus(pn)?pn:Ci}_anyControlsHaveStatus(r){return this._anyControls(e=>e.status===r)}_anyControlsDirty(){return this._anyControls(r=>r.dirty)}_anyControlsTouched(){return this._anyControls(r=>r.touched)}_updatePristine(r,e){let t=!this._anyControlsDirty(),i=this.pristine!==t;this.pristine=t,r.onlySelf||this._parent?._updatePristine(r,e),i&&this._events.next(new Si(this.pristine,e));}_updateTouched(r={},e){this.touched=this._anyControlsTouched(),this._events.next(new Ii(this.touched,e)),r.onlySelf||this._parent?._updateTouched(r,e);}_onDisabledChange=[];_registerOnCollectionChange(r){this._onCollectionChange=r;}_setUpdateStrategy(r){Cn(r)&&r.updateOn!=null&&(this._updateOn=r.updateOn);}_parentMarkedDirty(r){return !r&&!!this._parent?.dirty&&!this._parent._anyControlsDirty()}_find(r){return null}_assignValidators(r){this._rawValidators=Array.isArray(r)?r.slice():r,this._composedValidatorFn=Ul(this._rawValidators),this._updateHasRequiredValidator();}_assignAsyncValidators(r){this._rawAsyncValidators=Array.isArray(r)?r.slice():r,this._composedAsyncValidatorFn=Wl(this._rawAsyncValidators);}_updateHasRequiredValidator(){mw(()=>this._hasRequired.set(this.hasValidator(Gt.required)));}};function as(n,r){return Object.hasOwn(n,r)}function ql(n){return n.tagName==="INPUT"||n.tagName==="SELECT"||n.tagName==="TEXTAREA"}function Ql(n,r,e,t){switch(e){case "name":n.setAttribute(r,e,t);break;case "disabled":case "readonly":case "required":t?n.setAttribute(r,e,""):n.removeAttribute(r,e);break;case "max":case "min":case "minLength":case "maxLength":t!==void 0?n.setAttribute(r,e,t.toString()):n.removeAttribute(r,e);break}}var ha=class{kind;context;control;message;constructor({kind:r,context:e,control:t}){this.kind=r,this.context=e,this.control=t;}};function Yl(n){return typeof n=="number"?n:parseInt(n,10)}function rs(n){return typeof n=="number"?n:parseFloat(n)}var Dn=(()=>{class n{_validator=_n;_onChange;_enabled;ngOnChanges(e){if(this.inputName in e){let t=this.normalizeInput(e[this.inputName].currentValue);this._enabled=this.enabled(t),this._validator=this._enabled?this.createValidator(t):_n,this._onChange?.();}}validate(e){return this._validator(e)}registerOnValidatorChange(e){this._onChange=e;}enabled(e){return e!=null}static \u0275fac=function(t){return new(t||n)};static \u0275dir=SE({type:n,features:[zm$1]})}return n})(),Kl={provide:Ze,useExisting:vo(()=>$l),multi:true},$l=(()=>{class n extends Dn{max;inputName="max";normalizeInput=e=>rs(e);createValidator=e=>Uo(e);static \u0275fac=(()=>{let e;return function(i){return (e||(e=fy(n)))(i||n)}})();static \u0275dir=SE({type:n,selectors:[["input","type","number","max","","formControlName",""],["input","type","number","max","","formControl",""],["input","type","number","max","","ngModel",""]],hostVars:1,hostBindings:function(t,i){t&2&&Qp("max",i._enabled?i.max:null);},inputs:{max:"max"},standalone:false,features:[JD([Kl]),Hp$1]})}return n})(),Xl={provide:Ze,useExisting:vo(()=>Zl),multi:true},Zl=(()=>{class n extends Dn{min;inputName="min";normalizeInput=e=>rs(e);createValidator=e=>Go(e);static \u0275fac=(()=>{let e;return function(i){return (e||(e=fy(n)))(i||n)}})();static \u0275dir=SE({type:n,selectors:[["input","type","number","min","","formControlName",""],["input","type","number","min","","formControl",""],["input","type","number","min","","ngModel",""]],hostVars:1,hostBindings:function(t,i){t&2&&Qp("min",i._enabled?i.min:null);},inputs:{min:"min"},standalone:false,features:[JD([Xl]),Hp$1]})}return n})(),Jl={provide:Ze,useExisting:vo(()=>os),multi:true};var os=(()=>{class n extends Dn{required;inputName="required";normalizeInput=j0;createValidator=e=>Wo;enabled(e){return e}static \u0275fac=(()=>{let e;return function(i){return (e||(e=fy(n)))(i||n)}})();static \u0275dir=SE({type:n,selectors:[["","required","","formControlName","",3,"type","checkbox"],["","required","","formControl","",3,"type","checkbox"],["","required","","ngModel","",3,"type","checkbox"]],hostVars:1,hostBindings:function(t,i){t&2&&Qp("required",i._enabled?"":null);},inputs:{required:"required"},standalone:false,features:[JD([Jl]),Hp$1]})}return n})();var ed={provide:Ze,useExisting:vo(()=>td),multi:true},td=(()=>{class n extends Dn{maxlength;inputName="maxlength";normalizeInput=e=>Yl(e);createValidator=e=>qo(e);static \u0275fac=(()=>{let e;return function(i){return (e||(e=fy(n)))(i||n)}})();static \u0275dir=SE({type:n,selectors:[["","maxlength","","formControlName",""],["","maxlength","","formControl",""],["","maxlength","","ngModel",""]],hostVars:1,hostBindings:function(t,i){t&2&&Qp("maxlength",i._enabled?i.maxlength:null);},inputs:{maxlength:"maxlength"},standalone:false,features:[JD([ed]),Hp$1]})}return n})();var id=new x(""),Ei=new x("",{factory:()=>Mn}),Mn="always";function xa(n,r){return [...r.path,n]}function ss(n,r,e=Mn){wa(n,r),r.valueAccessor.writeValue(n.value),(n.disabled||e==="always")&&r.valueAccessor.setDisabledState?.(n.disabled),ad(n,r),od(n,r),rd(n,r),nd(n,r);}function yn(n,r,e=true){let t=()=>{};r?.valueAccessor?.registerOnChange(t),r?.valueAccessor?.registerOnTouched(t),wn(n,r),n&&(r._invokeOnDestroyCallbacks(),n._registerOnCollectionChange(()=>{}));}function xn(n,r){n.forEach(e=>{e.registerOnValidatorChange&&e.registerOnValidatorChange(r);});}function nd(n,r){if(r.valueAccessor.setDisabledState){let e=t=>{r.valueAccessor.setDisabledState(t);};n.registerOnDisabledChange(e),r._registerOnDestroy(()=>{n._unregisterOnDisabledChange(e);});}}function wa(n,r){let e=es(n);r.validator!==null?n.setValidators(Oo(e,r.validator)):typeof e=="function"&&n.setValidators([e]);let t=ts(n);r.asyncValidator!==null?n.setAsyncValidators(Oo(t,r.asyncValidator)):typeof t=="function"&&n.setAsyncValidators([t]);let i=()=>n.updateValueAndValidity();xn(r._rawValidators,i),xn(r._rawAsyncValidators,i);}function wn(n,r){let e=false;if(n!==null){if(r.validator!==null){let i=es(n);if(Array.isArray(i)&&i.length>0){let a=i.filter(o=>o!==r.validator);a.length!==i.length&&(e=true,n.setValidators(a));}}if(r.asyncValidator!==null){let i=ts(n);if(Array.isArray(i)&&i.length>0){let a=i.filter(o=>o!==r.asyncValidator);a.length!==i.length&&(e=true,n.setAsyncValidators(a));}}}let t=()=>{};return xn(r._rawValidators,t),xn(r._rawAsyncValidators,t),e}function ad(n,r){r.valueAccessor.registerOnChange(e=>{n._pendingValue=e,n._pendingChange=true,n._pendingDirty=true,n.updateOn==="change"&&ls(n,r);});}function rd(n,r){r.valueAccessor.registerOnTouched(()=>{n._pendingTouched=true,n.updateOn==="blur"&&n._pendingChange&&ls(n,r),n.updateOn!=="submit"&&n.markAsTouched();});}function ls(n,r){n._pendingDirty&&n.markAsDirty(),n.setValue(n._pendingValue,{emitModelToViewChange:false}),r.viewToModelUpdate(n._pendingValue),n._pendingChange=false;}function od(n,r){let e=(t,i)=>{r.valueAccessor.writeValue(t),i&&r.viewToModelUpdate(t);};n.registerOnChange(e),r._registerOnDestroy(()=>{n._unregisterOnChange(e);});}function ds(n,r){wa(n,r);}function sd(n,r){return wn(n,r)}function cs(n,r){if(!n.hasOwnProperty("model"))return  false;let e=n.model;return e.isFirstChange()?true:!Object.is(r,e.currentValue)}function ld(n){return Object.getPrototypeOf(n.constructor)===Ho}function ms(n,r){n._syncPendingControls(),r.forEach(e=>{let t=e.control;t.updateOn==="submit"&&t._pendingChange&&(e.viewToModelUpdate(t._pendingValue),t._pendingChange=false);});}function dd(n,r){if(!r)return null;let e,t,i;return r.forEach(a=>{a.constructor===jo?e=a:ld(a)?t=a:i=a;}),i||t||e||null}function cd(n,r){let e=n.indexOf(r);e>-1&&n.splice(e,1);}var hs={provide:id,useFactory:()=>{let n=T(lt,{self:true});return {setParseErrors:r=>{n.setParseErrorSource(r);},set onReset(r){n.onReset=r;}}}},lt=class extends gn{_parent=null;name=null;valueAccessor=null;isCustomControlBased=false;userOnReset;resetSubscription;set onReset(r){this.userOnReset=r,this.resetSubscription?.unsubscribe(),this.resetSubscription=void 0,this.control&&(this.resetSubscription=this.control.events.subscribe(e=>{e instanceof Ut&&this.control&&this.userOnReset?.(this.control.value);}),this.subscription?.add(this.resetSubscription));}isNativeFormElement=false;rawValueAccessors;_selectedValueAccessor=null;get selectedValueAccessor(){return this._selectedValueAccessor??=dd(this,this.rawValueAccessors)}parseErrorsValidator=null;renderer;injector;requiredValidatorViaDi;subscription;customControlBindings=null;constructor(r,e,t){super(),this.injector=r,this.renderer=e,this.rawValueAccessors=t,this.injector?.get(Fe)?.onDestroy(()=>{this.removeParseErrorsValidator(this.control),this.subscription?.unsubscribe();});}setupCustomControl(){this.subscription?.unsubscribe();let r=this.injector?.get(L0);if(!this.control||!r)return;let e=r.markForCheck.bind(r);this.subscription=new j,this.subscription.add(this.control.valueChanges.subscribe(e)),this.subscription.add(this.control.statusChanges.subscribe(e)),this.resetSubscription?.unsubscribe(),this.resetSubscription=void 0,this.userOnReset&&(this.resetSubscription=this.control.events.subscribe(t=>{t instanceof Ut&&this.control&&this.userOnReset?.(this.control.value);}),this.subscription.add(this.resetSubscription)),this.parseErrorsValidator&&this.control.addValidators(this.parseErrorsValidator);}ngControlCreate(r){!r.nativeElement.hasAttribute?.("ngNoCva")&&(this.rawValueAccessors&&this.rawValueAccessors.length>0||this.valueAccessor!==null)||!r.customControl||(this.isCustomControlBased=true,r.listenToCustomControlModel(i=>{this.control?.setValue(i,{emitModelToViewChange:false}),this.control?.markAsDirty(),this.viewToModelUpdate(i);}),r.listenToCustomControlOutput("touch",()=>{this.control?.markAsTouched();}),this.customControlBindings={},this.isNativeFormElement=ql(r.nativeElement),this.requiredValidatorViaDi=this._rawValidators.find(i=>i instanceof os));}ngControlUpdate(r,e){if(!this.isCustomControlBased)return;let t=this.control,i=this.customControlBindings;Object.is(i.value,t.value)||(i.value=t.value,r.setCustomControlModelInput(t.value)),this.bindControlProperty(r,i,"touched",t.touched),this.bindControlProperty(r,i,"dirty",t.dirty),this.bindControlProperty(r,i,"valid",t.valid),this.bindControlProperty(r,i,"invalid",t.invalid),this.bindControlProperty(r,i,"pending",t.pending),this.bindControlProperty(r,i,"disabled",t.disabled),this.shouldBindRequired&&this.bindControlProperty(r,i,"required",this.isRequired);let a=t.errors;if(i.errors!==a){i.errors=a;let o=this._convertErrors(a);r.setInputOnDirectives("errors",o);}}get isRequired(){return (this.requiredValidatorViaDi?._enabled||this.control?._hasRequired())??false}get shouldBindRequired(){return  true}bindControlProperty(r,e,t,i){if(e[t]===i)return;e[t]=i;let a=r.setInputOnDirectives(t,i);this.isNativeFormElement&&!a&&(t==="disabled"||t==="required")&&this.renderer&&Ql(this.renderer,r.nativeElement,t,i);}_convertErrors(r){if(r===null)return [];let e=this.control;return Object.entries(r).map(([t,i])=>new ha({context:i,kind:t,control:e}))}setParseErrorSource(r){if(r===void 0)return;let e=null,t=gw(()=>{let i=r();return i.length===0?null:i.reduce((a,o)=>(a[o.kind]=o,a),{})});this.parseErrorsValidator=(()=>e).bind(this),nd$1(()=>{e=t(),this.control?.updateValueAndValidity({emitEvent:false});},{injector:this.injector});}removeParseErrorsValidator(r){this.parseErrorsValidator&&(r?.removeValidators(this.parseErrorsValidator),r?.updateValueAndValidity({emitEvent:false}));}},kn=class{_cd;constructor(r){this._cd=r;}get isTouched(){return this._cd?.control?._touched?.(),!!this._cd?.control?.touched}get isUntouched(){return !!this._cd?.control?.untouched}get isPristine(){return this._cd?.control?._pristine?.(),!!this._cd?.control?.pristine}get isDirty(){return !!this._cd?.control?.dirty}get isValid(){return this._cd?.control?._status?.(),!!this._cd?.control?.valid}get isInvalid(){return !!this._cd?.control?.invalid}get isPending(){return !!this._cd?.control?.pending}get isSubmitted(){return this._cd?._submitted?.(),!!this._cd?.submitted}};var ou=(()=>{class n extends kn{constructor(e){super(e);}static \u0275fac=function(t){return new(t||n)(Sr$1(lt,2))};static \u0275dir=SE({type:n,selectors:[["","formControlName",""],["","ngModel",""],["","formControl",""]],hostVars:14,hostBindings:function(t,i){t&2&&fh$1("ng-untouched",i.isUntouched)("ng-touched",i.isTouched)("ng-pristine",i.isPristine)("ng-dirty",i.isDirty)("ng-valid",i.isValid)("ng-invalid",i.isInvalid)("ng-pending",i.isPending);},standalone:false,features:[Hp$1]})}return n})(),su=(()=>{class n extends kn{constructor(e){super(e);}static \u0275fac=function(t){return new(t||n)(Sr$1(Ge,10))};static \u0275dir=SE({type:n,selectors:[["","formGroupName",""],["","formArrayName",""],["","ngModelGroup",""],["","formGroup",""],["","formArray",""],["form",3,"ngNoForm",""],["","ngForm",""]],hostVars:16,hostBindings:function(t,i){t&2&&fh$1("ng-untouched",i.isUntouched)("ng-touched",i.isTouched)("ng-pristine",i.isPristine)("ng-dirty",i.isDirty)("ng-valid",i.isValid)("ng-invalid",i.isInvalid)("ng-pending",i.isPending)("ng-submitted",i.isSubmitted);},standalone:false,features:[Hp$1]})}return n})(),pi=class extends hi{constructor(r,e,t){super(va(e),ya(t,e)),this.controls=r,this._initObservables(),this._setUpdateStrategy(e),this._setUpControls(),this.updateValueAndValidity({onlySelf:true,emitEvent:!!this.asyncValidator});}controls;registerControl(r,e){let t=this._find(r);return t||(this.controls[r]=e,e.setParent(this),e._registerOnCollectionChange(this._onCollectionChange),e)}addControl(r,e,t={}){this.registerControl(r,e),this.updateValueAndValidity({emitEvent:t.emitEvent}),this._onCollectionChange();}removeControl(r,e={}){let t=this._find(r);t&&t._registerOnCollectionChange(()=>{}),delete this.controls[r],this.updateValueAndValidity({emitEvent:e.emitEvent}),this._onCollectionChange();}setControl(r,e,t={}){let i=this._find(r);i&&i._registerOnCollectionChange(()=>{}),delete this.controls[r],e&&this.registerControl(r,e),this.updateValueAndValidity({emitEvent:t.emitEvent}),this._onCollectionChange();}contains(r){return this._find(r)?.enabled===true}setValue(r,e={}){mw(()=>{ns(this,true,r),Object.keys(r).forEach(t=>{is(this,true,t),this.controls[t].setValue(r[t],{onlySelf:true,emitEvent:e.emitEvent});}),this.updateValueAndValidity(e);});}patchValue(r,e={}){r!=null&&(Object.keys(r).forEach(t=>{let i=this._find(t);i&&i.patchValue(r[t],{onlySelf:true,emitEvent:e.emitEvent});}),this.updateValueAndValidity(e));}reset(r={},e={}){this._forEachChild((t,i)=>{t.reset(r?r[i]:null,W(q({},e),{onlySelf:true}));}),this._updatePristine(e,this),this._updateTouched(e,this),this.updateValueAndValidity(e),e?.emitEvent!==false&&this._events.next(new Ut(this));}getRawValue(){return this._reduceChildren({},(r,e,t)=>(r[t]=e.getRawValue(),r))}_syncPendingControls(){let r=this._reduceChildren(false,(e,t)=>t._syncPendingControls()?true:e);return r&&this.updateValueAndValidity({onlySelf:true}),r}_forEachChild(r){Object.keys(this.controls).forEach(e=>{let t=this.controls[e];t&&r(t,e);});}_setUpControls(){this._forEachChild(r=>{r.setParent(this),r._registerOnCollectionChange(this._onCollectionChange);});}_updateValue(){this.value=this._reduceValue();}_anyControls(r){for(let[e,t]of Object.entries(this.controls))if(this.contains(e)&&r(t))return  true;return  false}_reduceValue(){let r={};return this._reduceChildren(r,(e,t,i)=>((t.enabled||this.disabled)&&(e[i]=t.value),e))}_reduceChildren(r,e){let t=r;return this._forEachChild((i,a)=>{t=e(t,i,a);}),t}_allControlsDisabled(){for(let r of Object.keys(this.controls))if(this.controls[r].enabled)return  false;return Object.keys(this.controls).length>0||this.disabled}_find(r){return as(this.controls,r)?this.controls[r]:null}};var pa=class extends pi{};var md={provide:Ge,useExisting:vo(()=>Ti)},Mi=Promise.resolve(),Ti=(()=>{class n extends Ge{callSetDisabledState;get submitted(){return mw(this.submittedReactive)}_submitted=gw(()=>this.submittedReactive());submittedReactive=Vo$1(false);_directives=new Set;form;ngSubmit=new $e;options;constructor(e,t,i){super(),this.callSetDisabledState=i,this.form=new pi({},ga(e),ba(t));}ngAfterViewInit(){this._setUpdateStrategy();}get formDirective(){return this}get control(){return this.form}get path(){return []}get controls(){return this.form.controls}addControl(e){Mi.then(()=>{let t=this._findContainer(e.path);e.control=t.registerControl(e.name,e.control),e._setupWithForm(this.callSetDisabledState),e.control.updateValueAndValidity({emitEvent:false}),this._directives.add(e);});}getControl(e){return this.form.get(e.path)}removeControl(e){Mi.then(()=>{this._findContainer(e.path)?.removeControl(e.name),this._directives.delete(e);});}addFormGroup(e){Mi.then(()=>{let t=this._findContainer(e.path),i=new pi({});ds(i,e),t.registerControl(e.name,i),i.updateValueAndValidity({emitEvent:false});});}removeFormGroup(e){Mi.then(()=>{this._findContainer(e.path)?.removeControl?.(e.name);});}getFormGroup(e){return this.form.get(e.path)}updateModel(e,t){Mi.then(()=>{this.form.get(e.path).setValue(t);});}setValue(e){this.control.setValue(e);}onSubmit(e){return this.submittedReactive.set(true),ms(this.form,this._directives),this.ngSubmit.emit(e),this.form._events.next(new vn(this.control)),e?.target?.method==="dialog"}onReset(){this.resetForm();}resetForm(e=void 0){this.form.reset(e),this.submittedReactive.set(false);}_setUpdateStrategy(){this.options&&this.options.updateOn!=null&&(this.form._updateOn=this.options.updateOn);}_findContainer(e){return e.pop(),e.length?this.form.get(e):this.form}static \u0275fac=function(t){return new(t||n)(Sr$1(Ze,10),Sr$1(ui,10),Sr$1(Ei,8))};static \u0275dir=SE({type:n,selectors:[["form",3,"ngNoForm","",3,"formGroup","",3,"formArray",""],["ng-form"],["","ngForm",""]],hostBindings:function(t,i){t&1&&nh$1("submit",function(o){return i.onSubmit(o)})("reset",function(){return i.onReset()});},inputs:{options:[0,"ngFormOptions","options"]},outputs:{ngSubmit:"ngSubmit"},exportAs:["ngForm"],standalone:false,features:[JD([md]),Hp$1]})}return n})();function Lo(n,r){let e=n.indexOf(r);e>-1&&n.splice(e,1);}function No(n){return typeof n=="object"&&n!==null&&Object.keys(n).length===2&&"value"in n&&"disabled"in n}var un=class extends hi{defaultValue=null;_onChange=[];_pendingValue;_pendingChange=false;constructor(r=null,e,t){super(va(e),ya(t,e)),this._applyFormState(r),this._setUpdateStrategy(e),this._initObservables(),this.updateValueAndValidity({onlySelf:true,emitEvent:!!this.asyncValidator}),Cn(e)&&(e.nonNullable||e.initialValueIsDefault)&&(No(r)?this.defaultValue=r.value:this.defaultValue=r);}setValue(r,e={}){mw(()=>{this.value=this._pendingValue=r,this._onChange.length&&e.emitModelToViewChange!==false&&this._onChange.forEach(t=>t(this.value,e.emitViewToModelChange!==false)),this.updateValueAndValidity(e);});}patchValue(r,e={}){this.setValue(r,e);}reset(r=this.defaultValue,e={}){this._applyFormState(r),this.markAsPristine(e),this.markAsUntouched(e),this.setValue(this.value,e),e.overwriteDefaultValue&&(this.defaultValue=this.value),this._pendingChange=false,e?.emitEvent!==false&&this._events.next(new Ut(this));}_updateValue(){}_anyControls(r){return  false}_allControlsDisabled(){return this.disabled}registerOnChange(r){this._onChange.push(r);}_unregisterOnChange(r){Lo(this._onChange,r);}registerOnDisabledChange(r){this._onDisabledChange.push(r);}_unregisterOnDisabledChange(r){Lo(this._onDisabledChange,r);}_forEachChild(r){}_syncPendingControls(){return this.updateOn==="submit"&&(this._pendingDirty&&this.markAsDirty(),this._pendingTouched&&this.markAsTouched(),this._pendingChange)?(this.setValue(this._pendingValue,{onlySelf:true,emitModelToViewChange:false}),true):false}_applyFormState(r){No(r)?(this.value=this._pendingValue=r.value,r.disabled?this.disable({onlySelf:true,emitEvent:false}):this.enable({onlySelf:true,emitEvent:false})):this.value=this._pendingValue=r;}};var hd=n=>n instanceof un,pd=(()=>{class n extends Ge{_parent;ngOnInit(){this._checkParentType(),this.formDirective.addFormGroup(this);}ngOnDestroy(){this.formDirective?.removeFormGroup(this);}get control(){return this.formDirective.getFormGroup(this)}get path(){return xa(this.name==null?this.name:this.name.toString(),this._parent)}get formDirective(){return this._parent?this._parent.formDirective:null}_checkParentType(){}static \u0275fac=(()=>{let e;return function(i){return (e||(e=fy(n)))(i||n)}})();static \u0275dir=SE({type:n,standalone:false,features:[Hp$1]})}return n})();var du=(()=>{class n{static \u0275fac=function(t){return new(t||n)};static \u0275dir=SE({type:n,selectors:[["form",3,"ngNoForm","",3,"ngNativeValidate",""]],hostAttrs:["novalidate",""],standalone:false})}return n})(),ud={provide:Xe,useExisting:vo(()=>_d),multi:true},_d=(()=>{class n extends Ho{writeValue(e){let t=e??"";this.setProperty("value",t);}registerOnChange(e){this.onChange=t=>{e(t==""?null:parseFloat(t));};}static \u0275fac=(()=>{let e;return function(i){return (e||(e=fy(n)))(i||n)}})();static \u0275dir=SE({type:n,selectors:[["input","type","number","formControlName","",3,"ngNoCva",""],["input","type","number","formControl","",3,"ngNoCva",""],["input","type","number","ngModel","",3,"ngNoCva",""]],hostBindings:function(t,i){t&1&&nh$1("input",function(o){return i.onChange(o.target.value)})("blur",function(){return i.onTouched()});},standalone:false,features:[JD([ud]),Hp$1]})}return n})();var ua=class extends hi{constructor(r,e,t){super(va(e),ya(t,e)),this.controls=r,this._initObservables(),this._setUpdateStrategy(e),this._setUpControls(),this.updateValueAndValidity({onlySelf:true,emitEvent:!!this.asyncValidator});}controls;at(r){return this.controls[this._adjustIndex(r)]}push(r,e={}){Array.isArray(r)?r.forEach(t=>{this.controls.push(t),this._registerControl(t);}):(this.controls.push(r),this._registerControl(r)),this.updateValueAndValidity({emitEvent:e.emitEvent}),this._onCollectionChange();}insert(r,e,t={}){this.controls.splice(r,0,e),this._registerControl(e),this.updateValueAndValidity({emitEvent:t.emitEvent});}removeAt(r,e={}){let t=this._adjustIndex(r);t<0&&(t=0),this.controls[t]&&this.controls[t]._registerOnCollectionChange(()=>{}),this.controls.splice(t,1),this.updateValueAndValidity({emitEvent:e.emitEvent});}setControl(r,e,t={}){let i=this._adjustIndex(r);i<0&&(i=0),this.controls[i]&&this.controls[i]._registerOnCollectionChange(()=>{}),this.controls.splice(i,1),e&&(this.controls.splice(i,0,e),this._registerControl(e)),this.updateValueAndValidity({emitEvent:t.emitEvent}),this._onCollectionChange();}get length(){return this.controls.length}setValue(r,e={}){mw(()=>{ns(this,false,r),r.forEach((t,i)=>{is(this,false,i),this.at(i).setValue(t,{onlySelf:true,emitEvent:e.emitEvent});}),this.updateValueAndValidity(e);});}patchValue(r,e={}){r!=null&&(r.forEach((t,i)=>{this.at(i)&&this.at(i).patchValue(t,{onlySelf:true,emitEvent:e.emitEvent});}),this.updateValueAndValidity(e));}reset(r=[],e={}){this._forEachChild((t,i)=>{t.reset(r[i],W(q({},e),{onlySelf:true}));}),this._updatePristine(e,this),this._updateTouched(e,this),this.updateValueAndValidity(e),e?.emitEvent!==false&&this._events.next(new Ut(this));}getRawValue(){return this.controls.map(r=>r.getRawValue())}clear(r={}){this.controls.length<1||(this._forEachChild(e=>e._registerOnCollectionChange(()=>{})),this.controls.splice(0),this.updateValueAndValidity({emitEvent:r.emitEvent}));}_adjustIndex(r){return r<0?r+this.length:r}_syncPendingControls(){let r=this.controls.reduce((e,t)=>t._syncPendingControls()?true:e,false);return r&&this.updateValueAndValidity({onlySelf:true}),r}_forEachChild(r){this.controls.forEach((e,t)=>{r(e,t);});}_updateValue(){this.value=this.controls.filter(r=>r.enabled||this.disabled).map(r=>r.value);}_anyControls(r){return this.controls.some(e=>e.enabled&&r(e))}_setUpControls(){this._forEachChild(r=>this._registerControl(r));}_allControlsDisabled(){for(let r of this.controls)if(r.enabled)return  false;return this.controls.length>0||this.disabled}_registerControl(r){r.setParent(this),r._registerOnCollectionChange(this._onCollectionChange);}_find(r){return this.at(r)??null}};var ps=(()=>{class n extends Ge{callSetDisabledState;get submitted(){return mw(this._submittedReactive)}set submitted(e){this._submittedReactive.set(e);}_submitted=gw(()=>this._submittedReactive());_submittedReactive=Vo$1(false);_oldForm;_onCollectionChange=()=>this._updateDomValue();directives=[];constructor(e,t,i){super(),this.callSetDisabledState=i,this._setValidators(e),this._setAsyncValidators(t);}ngOnChanges(e){this.onChanges(e);}ngOnDestroy(){this.onDestroy();}onChanges(e){this._checkFormPresent(),e.hasOwnProperty("form")&&(this._updateValidators(),this._updateDomValue(),this._updateRegistrations(),this._oldForm=this.form);}onDestroy(){this.form&&(wn(this.form,this),this.form._onCollectionChange===this._onCollectionChange&&this.form._registerOnCollectionChange(()=>{}));}get formDirective(){return this}get path(){return []}addControl(e){let t=this.form.get(e.path);return e._setupWithForm(t,this.callSetDisabledState),t.updateValueAndValidity({emitEvent:false}),this.directives.push(e),t}getControl(e){return this.form.get(e.path)}removeControl(e){yn(e.control||null,e,false),cd(this.directives,e);}addFormGroup(e){this._setUpFormContainer(e);}removeFormGroup(e){this._cleanUpFormContainer(e);}getFormGroup(e){return this.form.get(e.path)}getFormArray(e){return this.form.get(e.path)}addFormArray(e){this._setUpFormContainer(e);}removeFormArray(e){this._cleanUpFormContainer(e);}updateModel(e,t){this.form.get(e.path).setValue(t);}onReset(){this.resetForm();}resetForm(e=void 0,t={}){this.form.reset(e,t),this._submittedReactive.set(false);}onSubmit(e){return this.submitted=true,ms(this.form,this.directives),this.ngSubmit.emit(e),this.form._events.next(new vn(this.control)),e?.target?.method==="dialog"}_updateDomValue(){this.directives.forEach(e=>{let t=e.control,i=this.form.get(e.path);t!==i&&(yn(t||null,e),hd(i)&&e._setupWithForm(i,this.callSetDisabledState));}),this.form._updateTreeValidity({emitEvent:false});}_setUpFormContainer(e){let t=this.form.get(e.path);ds(t,e),t.updateValueAndValidity({emitEvent:false});}_cleanUpFormContainer(e){let t=this.form?.get(e.path);t&&sd(t,e)&&t.updateValueAndValidity({emitEvent:false});}_updateRegistrations(){this.form._registerOnCollectionChange(this._onCollectionChange),this._oldForm?._registerOnCollectionChange(()=>{});}_updateValidators(){wa(this.form,this),this._oldForm&&wn(this._oldForm,this);}_checkFormPresent(){this.form;}static \u0275fac=function(t){return new(t||n)(Sr$1(Ze,10),Sr$1(ui,10),Sr$1(Ei,8))};static \u0275dir=SE({type:n,features:[Hp$1,zm$1]})}return n})();var ka=new x(""),fd={provide:lt,useExisting:vo(()=>gd)},gd=(()=>{class n extends lt{_ngModelWarningConfig;callSetDisabledState;viewModel;form;set isDisabled(e){}model;update=new $e;static _ngModelWarningSentOnce=false;_ngModelWarningSent=false;constructor(e,t,i,a,o,c,y){super(y,c,i),this._ngModelWarningConfig=a,this.callSetDisabledState=o,this._setValidators(e),this._setAsyncValidators(t);}ngOnChanges(e){if(this._isControlChanged(e)){let t=e.form.previousValue;t&&(yn(t,this,false),this.removeParseErrorsValidator(t)),this.isCustomControlBased?this.setupCustomControl():(this.valueAccessor??=this.selectedValueAccessor,ss(this.form,this,this.callSetDisabledState)),this.form.updateValueAndValidity({emitEvent:false});}cs(e,this.viewModel)&&(this.form.setValue(this.model),this.viewModel=this.model);}ngOnDestroy(){this.form&&yn(this.form,this,false);}get path(){return []}get control(){return this.form}viewToModelUpdate(e){this.viewModel=e,this.update.emit(e);}_isControlChanged(e){return e.hasOwnProperty("form")}\u0275ngControlCreate(e){super.ngControlCreate(e);}\u0275ngControlUpdate(e){super.ngControlUpdate(e,true);}static \u0275fac=function(t){return new(t||n)(Sr$1(Ze,10),Sr$1(ui,10),Sr$1(Xe,10),Sr$1(ka,8),Sr$1(Ei,8),Sr$1(MI,8),Sr$1(ge,8))};static \u0275dir=SE({type:n,selectors:[["","formControl",""]],inputs:{form:[0,"formControl","form"],isDisabled:[0,"disabled","isDisabled"],model:[0,"ngModel","model"]},outputs:{update:"ngModelChange"},exportAs:["ngForm"],standalone:false,features:[JD([fd,hs]),Hp$1,zm$1,AE(null)]})}return n})(),bd={provide:Ge,useExisting:vo(()=>us)},us=(()=>{class n extends pd{name=null;constructor(e,t,i){super(),this._parent=e,this._setValidators(t),this._setAsyncValidators(i);}_checkParentType(){fs(this._parent);}static \u0275fac=function(t){return new(t||n)(Sr$1(Ge,13),Sr$1(Ze,10),Sr$1(ui,10))};static \u0275dir=SE({type:n,selectors:[["","formGroupName",""]],inputs:{name:[0,"formGroupName","name"]},standalone:false,features:[JD([bd]),Hp$1]})}return n})(),vd={provide:Ge,useExisting:vo(()=>_s)},_s=(()=>{class n extends Ge{_parent;name=null;constructor(e,t,i){super(),this._parent=e,this._setValidators(t),this._setAsyncValidators(i);}ngOnInit(){fs(this._parent),this.formDirective.addFormArray(this);}ngOnDestroy(){this.formDirective?.removeFormArray(this);}get control(){return this.formDirective.getFormArray(this)}get formDirective(){return this._parent?this._parent.formDirective:null}get path(){return xa(this.name==null?this.name:this.name.toString(),this._parent)}static \u0275fac=function(t){return new(t||n)(Sr$1(Ge,13),Sr$1(Ze,10),Sr$1(ui,10))};static \u0275dir=SE({type:n,selectors:[["","formArrayName",""]],inputs:{name:[0,"formArrayName","name"]},standalone:false,features:[JD([vd]),Hp$1]})}return n})();function fs(n){return !(n instanceof us)&&!(n instanceof ps)&&!(n instanceof _s)}var yd={provide:lt,useExisting:vo(()=>xd)},xd=(()=>{class n extends lt{_ngModelWarningConfig;_added=false;viewModel;control;name=null;set isDisabled(e){}model;update=new $e;static _ngModelWarningSentOnce=false;_ngModelWarningSent=false;constructor(e,t,i,a,o,c,y){super(y,c,a),this._ngModelWarningConfig=o,this._parent=e,this._setValidators(t),this._setAsyncValidators(i);}_setupWithForm(e,t){this.control=e,this.isCustomControlBased?this.setupCustomControl():(this.valueAccessor??=this.selectedValueAccessor,ss(e,this,t));}ngOnChanges(e){this._added||this._setUpControl(),cs(e,this.viewModel)&&(this.viewModel=this.model,this.formDirective.updateModel(this,this.model));}ngOnDestroy(){this.formDirective?.removeControl(this);}viewToModelUpdate(e){this.viewModel=e,this.update.emit(e);}get path(){return xa(this.name==null?this.name:this.name.toString(),this._parent)}get formDirective(){return this._parent?this._parent.formDirective:null}_setUpControl(){this.control=this.formDirective.addControl(this),this._added=true;}\u0275ngControlCreate(e){super.ngControlCreate(e);}\u0275ngControlUpdate(e){this.isCustomControlBased&&(this._added||this._setUpControl(),super.ngControlUpdate(e,true));}static \u0275fac=function(t){return new(t||n)(Sr$1(Ge,13),Sr$1(Ze,10),Sr$1(ui,10),Sr$1(Xe,10),Sr$1(ka,8),Sr$1(MI,8),Sr$1(ge,8))};static \u0275dir=SE({type:n,selectors:[["","formControlName",""]],inputs:{name:[0,"formControlName","name"],isDisabled:[0,"disabled","isDisabled"],model:[0,"ngModel","model"]},outputs:{update:"ngModelChange"},standalone:false,features:[JD([yd,hs]),Hp$1,zm$1,AE(null)]})}return n})();var wd={provide:Ge,useExisting:vo(()=>Ai)},Ai=(()=>{class n extends ps{form=null;ngSubmit=new $e;get control(){return this.form}static \u0275fac=(()=>{let e;return function(i){return (e||(e=fy(n)))(i||n)}})();static \u0275dir=SE({type:n,selectors:[["","formGroup",""]],hostBindings:function(t,i){t&1&&nh$1("submit",function(o){return i.onSubmit(o)})("reset",function(){return i.onReset()});},inputs:{form:[0,"formGroup","form"]},outputs:{ngSubmit:"ngSubmit"},exportAs:["ngForm"],standalone:false,features:[JD([wd]),Hp$1]})}return n})();var gs=(()=>{class n{static \u0275fac=function(t){return new(t||n)};static \u0275mod=bE({type:n});static \u0275inj=cu$1({})}return n})();function Bo(n){return !!n&&(n.asyncValidators!==void 0||n.validators!==void 0||n.updateOn!==void 0)}var cu=(()=>{class n{useNonNullable=false;get nonNullable(){let e=new n;return e.useNonNullable=true,e}group(e,t=null){let i=this._reduceControls(e),a={};return Bo(t)?a=t:t!==null&&(a.validators=t.validator,a.asyncValidators=t.asyncValidator),new pi(i,a)}record(e,t=null){let i=this._reduceControls(e);return new pa(i,t)}control(e,t,i){let a={};return this.useNonNullable?(Bo(t)?a=t:(a.validators=t,a.asyncValidators=i),new un(e,W(q({},a),{nonNullable:true}))):new un(e,t,i)}array(e,t,i){let a=e.map(o=>this._createControl(o));return new ua(a,t,i)}_reduceControls(e){let t={};return Object.keys(e).forEach(i=>{t[i]=this._createControl(e[i]);}),t}_createControl(e){if(e instanceof un)return e;if(e instanceof hi)return e;if(Array.isArray(e)){let t=e[0],i=e.length>1?e[1]:null,a=e.length>2?e[2]:null;return this.control(t,i,a)}else return this.control(e)}static \u0275fac=function(t){return new(t||n)};static \u0275prov=Er$1({token:n,factory:n.\u0275fac})}return n})();var mu=(()=>{class n{static withConfig(e){return {ngModule:n,providers:[{provide:Ei,useValue:e.callSetDisabledState??Mn}]}}static \u0275fac=function(t){return new(t||n)};static \u0275mod=bE({type:n});static \u0275inj=cu$1({imports:[gs]})}return n})(),hu=(()=>{class n{static withConfig(e){return {ngModule:n,providers:[{provide:ka,useValue:e.warnOnNgModelWithFormControl??"always"},{provide:Ei,useValue:e.callSetDisabledState??Mn}]}}static \u0275fac=function(t){return new(t||n)};static \u0275mod=bE({type:n});static \u0275inj=cu$1({imports:[gs]})}return n})();var Ca=class{_box;_destroyed=new ne;_resizeSubject=new ne;_resizeObserver;_elementObservables=new Map;constructor(r){this._box=r,typeof ResizeObserver<"u"&&(this._resizeObserver=new ResizeObserver(e=>this._resizeSubject.next(e)));}observe(r){return this._elementObservables.has(r)||this._elementObservables.set(r,new _(e=>{let t=this._resizeSubject.subscribe(e);return this._resizeObserver?.observe(r,{box:this._box}),()=>{this._resizeObserver?.unobserve(r),t.unsubscribe(),this._elementObservables.delete(r);}}).pipe(en(e=>e.some(t=>t.target===r)),Hg({bufferSize:1,refCount:true}),Bl$1(this._destroyed))),this._elementObservables.get(r)}destroy(){this._destroyed.next(),this._destroyed.complete(),this._resizeSubject.complete(),this._elementObservables.clear();}},Sn=(()=>{class n{_cleanupErrorListener;_observers=new Map;_ngZone=T(Se);constructor(){}ngOnDestroy(){for(let[,e]of this._observers)e.destroy();this._observers.clear(),this._cleanupErrorListener?.();}observe(e,t){let i=t?.box||"content-box";return this._observers.has(i)||this._observers.set(i,new Ca(i)),this._observers.get(i).observe(e)}static \u0275fac=function(t){return new(t||n)};static \u0275prov=Er$1({token:n,factory:n.\u0275fac})}return n})();var kd=["notch"],Cd=["*"],bs=["iconPrefixContainer"],vs=["textPrefixContainer"],ys=["iconSuffixContainer"],xs=["textSuffixContainer"],Dd=["textField"],Md=["*",[["mat-label"]],[["","matPrefix",""],["","matIconPrefix",""]],[["","matTextPrefix",""]],[["","matTextSuffix",""]],[["","matSuffix",""],["","matIconSuffix",""]],[["mat-error"],["","matError",""]],[["mat-hint",3,"align","end"]],[["mat-hint","align","end"]]],Sd=["*","mat-label","[matPrefix], [matIconPrefix]","[matTextPrefix]","[matTextSuffix]","[matSuffix], [matIconSuffix]","mat-error, [matError]","mat-hint:not([align='end'])","mat-hint[align='end']"];function Id(n,r){n&1&&Yp(0,"span",21);}function Ed(n,r){if(n&1&&(yi(0,"label",20),mD(1,1),QE(2,Id,1,0,"span",21),jc$1()),n&2){let e=pD(2);Zp("floating",e._shouldLabelFloat())("monitorResize",e._hasOutline())("id",e._labelId),Qp("for",e._control.disableAutomaticLabeling?null:e._control.id),Wv(2),ZE(!e.hideRequiredMarker&&e._control.required?2:-1);}}function Td(n,r){if(n&1&&QE(0,Ed,3,5,"label",20),n&2){let e=pD();ZE(e._hasFloatingLabel()?0:-1);}}function Ad(n,r){n&1&&Yp(0,"div",7);}function Rd(n,r){}function Fd(n,r){if(n&1&&$p(0,Rd,0,0,"ng-template",13),n&2){pD(2);let e=DD(1);Zp("ngTemplateOutlet",e);}}function Od(n,r){if(n&1&&(yi(0,"div",9),QE(1,Fd,1,1,null,13),jc$1()),n&2){let e=pD();Zp("matFormFieldNotchedOutlineOpen",e._shouldLabelFloat()),Wv(),ZE(e._forceDisplayInfixLabel()?-1:1);}}function Pd(n,r){n&1&&(yi(0,"div",10,2),mD(2,2),jc$1());}function Vd(n,r){n&1&&(yi(0,"div",11,3),mD(2,3),jc$1());}function Ld(n,r){}function Nd(n,r){if(n&1&&$p(0,Ld,0,0,"ng-template",13),n&2){pD();let e=DD(1);Zp("ngTemplateOutlet",e);}}function Bd(n,r){n&1&&(yi(0,"div",14,4),mD(2,4),jc$1());}function zd(n,r){n&1&&(yi(0,"div",15,5),mD(2,5),jc$1());}function Hd(n,r){n&1&&Yp(0,"div",16);}function jd(n,r){n&1&&(yi(0,"div",18),mD(1,6),jc$1());}function Gd(n,r){if(n&1&&(yi(0,"mat-hint",22),UD(1),jc$1()),n&2){let e=pD(2);Zp("id",e._hintLabelId),Wv(),Ih$1(e.hintLabel);}}function Ud(n,r){if(n&1&&(yi(0,"div",19),QE(1,Gd,2,2,"mat-hint",22),mD(2,7),Yp(3,"div",23),mD(4,8),jc$1()),n&2){let e=pD();Wv(),ZE(e.hintLabel?1:-1);}}var Da=(()=>{class n{static \u0275fac=function(t){return new(t||n)};static \u0275dir=SE({type:n,selectors:[["mat-label"]]})}return n})(),Is=new x("MatError"),Wd=(()=>{class n{id=T(Y).getId("mat-mdc-error-");static \u0275fac=function(t){return new(t||n)};static \u0275dir=SE({type:n,selectors:[["mat-error"],["","matError",""]],hostAttrs:[1,"mat-mdc-form-field-error","mat-mdc-form-field-bottom-align"],hostVars:1,hostBindings:function(t,i){t&2&&eh$1("id",i.id);},inputs:{id:"id"},features:[JD([{provide:Is,useExisting:n}])]})}return n})(),Ma=(()=>{class n{align="start";id=T(Y).getId("mat-mdc-hint-");static \u0275fac=function(t){return new(t||n)};static \u0275dir=SE({type:n,selectors:[["mat-hint"]],hostAttrs:[1,"mat-mdc-form-field-hint","mat-mdc-form-field-bottom-align"],hostVars:4,hostBindings:function(t,i){t&2&&(eh$1("id",i.id),Qp("align",null),fh$1("mat-mdc-form-field-hint-end",i.align==="end"));},inputs:{align:"align",id:"id"}})}return n})(),qd=new x("MatPrefix");var Es=new x("MatSuffix"),Qd=(()=>{class n{set _isTextSelector(e){this._isText=true;}_isText=false;static \u0275fac=function(t){return new(t||n)};static \u0275dir=SE({type:n,selectors:[["","matSuffix",""],["","matIconSuffix",""],["","matTextSuffix",""]],inputs:{_isTextSelector:[0,"matTextSuffix","_isTextSelector"]},features:[JD([{provide:Es,useExisting:n}])]})}return n})(),Ts=new x("FloatingLabelParent"),ws=(()=>{class n{_elementRef=T(Dr$1);get floating(){return this._floating}set floating(e){this._floating=e,this.monitorResize&&this._handleResize();}_floating=false;get monitorResize(){return this._monitorResize}set monitorResize(e){this._monitorResize=e,this._monitorResize?this._subscribeToResize():this._resizeSubscription.unsubscribe();}_monitorResize=false;_resizeObserver=T(Sn);_ngZone=T(Se);_parent=T(Ts);_resizeSubscription=new j;ngOnDestroy(){this._resizeSubscription.unsubscribe();}getWidth(){return Yd(this._elementRef.nativeElement)}get element(){return this._elementRef.nativeElement}_handleResize(){setTimeout(()=>this._parent._handleLabelResized());}_subscribeToResize(){this._resizeSubscription.unsubscribe(),this._ngZone.runOutsideAngular(()=>{this._resizeSubscription=this._resizeObserver.observe(this._elementRef.nativeElement,{box:"border-box"}).subscribe(()=>this._handleResize());});}static \u0275fac=function(t){return new(t||n)};static \u0275dir=SE({type:n,selectors:[["label","matFormFieldFloatingLabel",""]],hostAttrs:[1,"mdc-floating-label","mat-mdc-floating-label"],hostVars:2,hostBindings:function(t,i){t&2&&fh$1("mdc-floating-label--float-above",i.floating);},inputs:{floating:"floating",monitorResize:"monitorResize"}})}return n})();function Yd(n){let r=n;if(r.offsetParent!==null)return r.scrollWidth;let e=r.cloneNode(true);e.style.setProperty("position","absolute"),e.style.setProperty("transform","translate(-9999px, -9999px)"),document.documentElement.appendChild(e);let t=e.scrollWidth;return e.remove(),t}var ks="mdc-line-ripple--active",In="mdc-line-ripple--deactivating",Cs=(()=>{class n{_elementRef=T(Dr$1);_cleanupTransitionEnd;constructor(){let e=T(Se),t=T(MI);e.runOutsideAngular(()=>{this._cleanupTransitionEnd=t.listen(this._elementRef.nativeElement,"transitionend",this._handleTransitionEnd);});}activate(){let e=this._elementRef.nativeElement.classList;e.remove(In),e.add(ks);}deactivate(){this._elementRef.nativeElement.classList.add(In);}_handleTransitionEnd=e=>{let t=this._elementRef.nativeElement.classList,i=t.contains(In);e.propertyName==="opacity"&&i&&t.remove(ks,In);};ngOnDestroy(){this._cleanupTransitionEnd();}static \u0275fac=function(t){return new(t||n)};static \u0275dir=SE({type:n,selectors:[["div","matFormFieldLineRipple",""]],hostAttrs:[1,"mdc-line-ripple"]})}return n})(),Ds=(()=>{class n{_elementRef=T(Dr$1);_ngZone=T(Se);open=false;_notch;ngAfterViewInit(){let e=this._elementRef.nativeElement,t=e.querySelector(".mdc-floating-label");t?(e.classList.add("mdc-notched-outline--upgraded"),typeof requestAnimationFrame=="function"&&(t.style.transitionDuration="0s",this._ngZone.runOutsideAngular(()=>{requestAnimationFrame(()=>t.style.transitionDuration="");}))):e.classList.add("mdc-notched-outline--no-label");}_setNotchWidth(e){let t=this._notch.nativeElement;!this.open||!e?t.style.width="":t.style.width=`calc(${e}px * var(--mat-mdc-form-field-floating-label-scale, 0.75) + 9px)`;}_setMaxWidth(e){this._notch.nativeElement.style.setProperty("--mat-form-field-notch-max-width",`calc(100% - ${e}px)`);}static \u0275fac=function(t){return new(t||n)};static \u0275cmp=TE({type:n,selectors:[["div","matFormFieldNotchedOutline",""]],viewQuery:function(t,i){if(t&1&&sh$1(kd,5),t&2){let a;vD(a=ID())&&(i._notch=a.first);}},hostAttrs:[1,"mdc-notched-outline"],hostVars:2,hostBindings:function(t,i){t&2&&fh$1("mdc-notched-outline--notched",i.open);},inputs:{open:[0,"matFormFieldNotchedOutlineOpen","open"]},ngContentSelectors:Cd,decls:5,vars:0,consts:[["notch",""],[1,"mat-mdc-notch-piece","mdc-notched-outline__leading"],[1,"mat-mdc-notch-piece","mdc-notched-outline__notch"],[1,"mat-mdc-notch-piece","mdc-notched-outline__trailing"]],template:function(t,i){t&1&&(gD(),Kp(0,"div",1),Vc$1(1,"div",2,0),mD(3),Hc$1(),Kp(4,"div",3));},encapsulation:2})}return n})(),Ri=(()=>{class n{value=null;stateChanges;id;placeholder;ngControl=null;focused=false;empty=false;shouldLabelFloat=false;required=false;disabled=false;errorState=false;controlType;autofilled;userAriaDescribedBy;disableAutomaticLabeling;describedByIds;static \u0275fac=function(t){return new(t||n)};static \u0275dir=SE({type:n})}return n})();var Wt=new x("MatFormField"),Kd=new x("MAT_FORM_FIELD_DEFAULT_OPTIONS"),Ms="fill",$d="auto",Ss="fixed",Xd="translateY(-50%)",En=(()=>{class n{_elementRef=T(Dr$1);_changeDetectorRef=T(L0);_platform=T(D);_idGenerator=T(Y);_ngZone=T(Se);_defaults=T(Kd,{optional:true});_currentDirection;_textField;_iconPrefixContainer;_textPrefixContainer;_iconSuffixContainer;_textSuffixContainer;_floatingLabel;_notchedOutline;_lineRipple;_iconPrefixContainerSignal=R0("iconPrefixContainer");_textPrefixContainerSignal=R0("textPrefixContainer");_iconSuffixContainerSignal=R0("iconSuffixContainer");_textSuffixContainerSignal=R0("textSuffixContainer");_prefixSuffixContainers=gw(()=>[this._iconPrefixContainerSignal(),this._textPrefixContainerSignal(),this._iconSuffixContainerSignal(),this._textSuffixContainerSignal()].map(e=>e?.nativeElement).filter(e=>e!==void 0));_formFieldControl;_prefixChildren;_suffixChildren;_errorChildren;_hintChildren;_labelChild=O0(Da);get hideRequiredMarker(){return this._hideRequiredMarker}set hideRequiredMarker(e){this._hideRequiredMarker=mm$1(e);}_hideRequiredMarker=false;color="primary";get floatLabel(){return this._floatLabel||this._defaults?.floatLabel||$d}set floatLabel(e){e!==this._floatLabel&&(this._floatLabel=e,this._changeDetectorRef.markForCheck());}_floatLabel;get appearance(){return this._appearanceSignal()}set appearance(e){let t=e||this._defaults?.appearance||Ms;this._appearanceSignal.set(t);}_appearanceSignal=Vo$1(Ms);get subscriptSizing(){return this._subscriptSizing||this._defaults?.subscriptSizing||Ss}set subscriptSizing(e){this._subscriptSizing=e||this._defaults?.subscriptSizing||Ss;}_subscriptSizing=null;get hintLabel(){return this._hintLabel}set hintLabel(e){this._hintLabel=e,this._processHints();}_hintLabel="";_hasIconPrefix=false;_hasTextPrefix=false;_hasIconSuffix=false;_hasTextSuffix=false;_labelId=this._idGenerator.getId("mat-mdc-form-field-label-");_hintLabelId=this._idGenerator.getId("mat-mdc-hint-");_describedByIds;get _control(){return this._explicitFormFieldControl||this._formFieldControl}set _control(e){this._explicitFormFieldControl=e;}_destroyed=new ne;_isFocused=null;_explicitFormFieldControl;_previousControl=null;_previousControlValidatorFn=null;_stateChanges;_valueChanges;_describedByChanges;_outlineLabelOffsetResizeObserver=null;_animationsDisabled=B();constructor(){let e=this._defaults,t=T(Er$2);e&&(e.appearance&&(this.appearance=e.appearance),this._hideRequiredMarker=!!e?.hideRequiredMarker,e.color&&(this.color=e.color)),nd$1(()=>this._currentDirection=t.valueSignal()),this._syncOutlineLabelOffset();}ngAfterViewInit(){this._updateFocusState(),this._animationsDisabled||this._ngZone.runOutsideAngular(()=>{setTimeout(()=>{this._elementRef.nativeElement.classList.add("mat-form-field-animations-enabled");},300);}),this._changeDetectorRef.detectChanges();}ngAfterContentInit(){this._assertFormFieldControl(),this._initializeSubscript(),this._initializePrefixAndSuffix();}ngAfterContentChecked(){this._assertFormFieldControl(),this._control!==this._previousControl&&(this._initializeControl(this._previousControl),this._control.ngControl&&this._control.ngControl.control&&(this._previousControlValidatorFn=this._control.ngControl.control.validator),this._previousControl=this._control),this._control.ngControl&&this._control.ngControl.control&&this._control.ngControl.control.validator!==this._previousControlValidatorFn&&this._changeDetectorRef.markForCheck();}ngOnDestroy(){this._outlineLabelOffsetResizeObserver?.disconnect(),this._stateChanges?.unsubscribe(),this._valueChanges?.unsubscribe(),this._describedByChanges?.unsubscribe(),this._destroyed.next(),this._destroyed.complete();}getLabelId=gw(()=>this._hasFloatingLabel()?this._labelId:null);getConnectedOverlayOrigin(){return this._textField||this._elementRef}_animateAndLockLabel(){this._hasFloatingLabel()&&(this.floatLabel="always");}_initializeControl(e){let t=this._control,i="mat-mdc-form-field-type-";e&&this._elementRef.nativeElement.classList.remove(i+e.controlType),t.controlType&&this._elementRef.nativeElement.classList.add(i+t.controlType),this._stateChanges?.unsubscribe(),this._stateChanges=t.stateChanges.subscribe(()=>{this._updateFocusState(),this._changeDetectorRef.markForCheck();}),this._describedByChanges?.unsubscribe(),this._describedByChanges=t.stateChanges.pipe(Hl$1([void 0,void 0]),Ce(()=>[t.errorState,t.userAriaDescribedBy]),Vg(),en(([[a,o],[c,y]])=>a!==c||o!==y)).subscribe(()=>this._syncDescribedByIds()),this._valueChanges?.unsubscribe(),t.ngControl&&t.ngControl.valueChanges&&(this._valueChanges=t.ngControl.valueChanges.pipe(Bl$1(this._destroyed)).subscribe(()=>this._changeDetectorRef.markForCheck()));}_checkPrefixAndSuffixTypes(){this._hasIconPrefix=!!this._prefixChildren.find(e=>!e._isText),this._hasTextPrefix=!!this._prefixChildren.find(e=>e._isText),this._hasIconSuffix=!!this._suffixChildren.find(e=>!e._isText),this._hasTextSuffix=!!this._suffixChildren.find(e=>e._isText);}_initializePrefixAndSuffix(){this._checkPrefixAndSuffixTypes(),kg(this._prefixChildren.changes,this._suffixChildren.changes).subscribe(()=>{this._checkPrefixAndSuffixTypes(),this._changeDetectorRef.markForCheck();});}_initializeSubscript(){this._hintChildren.changes.subscribe(()=>{this._processHints(),this._changeDetectorRef.markForCheck();}),this._errorChildren.changes.subscribe(()=>{this._syncDescribedByIds(),this._changeDetectorRef.markForCheck();}),this._validateHints(),this._syncDescribedByIds();}_assertFormFieldControl(){this._control;}_updateFocusState(){let e=this._control.focused;e&&!this._isFocused?(this._isFocused=true,this._lineRipple?.activate()):!e&&(this._isFocused||this._isFocused===null)&&(this._isFocused=false,this._lineRipple?.deactivate()),this._elementRef.nativeElement.classList.toggle("mat-focused",e),this._textField?.nativeElement.classList.toggle("mdc-text-field--focused",e);}_syncOutlineLabelOffset(){H0({earlyRead:()=>{if(this._appearanceSignal()!=="outline")return this._outlineLabelOffsetResizeObserver?.disconnect(),null;if(globalThis.ResizeObserver){this._outlineLabelOffsetResizeObserver||=new globalThis.ResizeObserver(()=>{this._writeOutlinedLabelStyles(this._getOutlinedLabelOffset());});for(let e of this._prefixSuffixContainers())this._outlineLabelOffsetResizeObserver.observe(e,{box:"border-box"});}return this._getOutlinedLabelOffset()},write:e=>this._writeOutlinedLabelStyles(e())});}_shouldAlwaysFloat(){return this.floatLabel==="always"}_hasOutline(){return this.appearance==="outline"}_forceDisplayInfixLabel(){return !this._platform.isBrowser&&this._prefixChildren.length&&!this._shouldLabelFloat()}_hasFloatingLabel=gw(()=>!!this._labelChild());_shouldLabelFloat(){return this._hasFloatingLabel()?this._control.shouldLabelFloat||this._shouldAlwaysFloat():false}_shouldForward(e){let t=this._control?this._control.ngControl:null;return t&&t[e]}_getSubscriptMessageType(){return this._errorChildren&&this._errorChildren.length>0&&this._control.errorState?"error":"hint"}_handleLabelResized(){this._refreshOutlineNotchWidth();}_refreshOutlineNotchWidth(){!this._hasOutline()||!this._floatingLabel||!this._shouldLabelFloat()?this._notchedOutline?._setNotchWidth(0):this._notchedOutline?._setNotchWidth(this._floatingLabel.getWidth());}_processHints(){this._validateHints(),this._syncDescribedByIds();}_validateHints(){this._hintChildren;}_syncDescribedByIds(){if(this._control){let e=[];if(this._control.userAriaDescribedBy&&typeof this._control.userAriaDescribedBy=="string"&&e.push(...this._control.userAriaDescribedBy.split(" ")),this._getSubscriptMessageType()==="hint"){let a=this._hintChildren?this._hintChildren.find(c=>c.align==="start"):null,o=this._hintChildren?this._hintChildren.find(c=>c.align==="end"):null;a?e.push(a.id):this._hintLabel&&e.push(this._hintLabelId),o&&e.push(o.id);}else this._errorChildren&&e.push(...this._errorChildren.map(a=>a.id));let t=this._control.describedByIds,i;if(t){let a=this._describedByIds||e;i=e.concat(t.filter(o=>o&&!a.includes(o)));}else i=e;this._control.setDescribedByIds(i),this._describedByIds=e;}}_getOutlinedLabelOffset(){if(!this._hasOutline()||!this._floatingLabel)return null;if(!this._iconPrefixContainer&&!this._textPrefixContainer)return ["",null];if(!this._isAttachedToDom())return null;let e=this._iconPrefixContainer?.nativeElement,t=this._textPrefixContainer?.nativeElement,i=this._iconSuffixContainer?.nativeElement,a=this._textSuffixContainer?.nativeElement,o=e?.getBoundingClientRect().width??0,c=t?.getBoundingClientRect().width??0,y=i?.getBoundingClientRect().width??0,F=a?.getBoundingClientRect().width??0,H=this._currentDirection==="rtl"?"-1":"1",De=`${o+c}px`,Me=`calc(${H} * (${De} + var(--mat-mdc-form-field-label-offset-x, 0px)))`,ji=`var(--mat-mdc-form-field-label-transform, ${Xd} translateX(${Me}))`,Gi=o+c+y+F;return [ji,Gi]}_writeOutlinedLabelStyles(e){if(e!==null){let[t,i]=e;this._floatingLabel&&(this._floatingLabel.element.style.transform=t),i!==null&&this._notchedOutline?._setMaxWidth(i);}}_isAttachedToDom(){let e=this._elementRef.nativeElement;if(e.getRootNode){let t=e.getRootNode();return t&&t!==e}return document.documentElement.contains(e)}static \u0275fac=function(t){return new(t||n)};static \u0275cmp=TE({type:n,selectors:[["mat-form-field"]],contentQueries:function(t,i,a){if(t&1&&(ah$1(a,i._labelChild,Da,5),ih$1(a,Ri,5)(a,qd,5)(a,Es,5)(a,Is,5)(a,Ma,5)),t&2){ED();let o;vD(o=ID())&&(i._formFieldControl=o.first),vD(o=ID())&&(i._prefixChildren=o),vD(o=ID())&&(i._suffixChildren=o),vD(o=ID())&&(i._errorChildren=o),vD(o=ID())&&(i._hintChildren=o);}},viewQuery:function(t,i){if(t&1&&(ch$1(i._iconPrefixContainerSignal,bs,5)(i._textPrefixContainerSignal,vs,5)(i._iconSuffixContainerSignal,ys,5)(i._textSuffixContainerSignal,xs,5),sh$1(Dd,5)(bs,5)(vs,5)(ys,5)(xs,5)(ws,5)(Ds,5)(Cs,5)),t&2){ED(4);let a;vD(a=ID())&&(i._textField=a.first),vD(a=ID())&&(i._iconPrefixContainer=a.first),vD(a=ID())&&(i._textPrefixContainer=a.first),vD(a=ID())&&(i._iconSuffixContainer=a.first),vD(a=ID())&&(i._textSuffixContainer=a.first),vD(a=ID())&&(i._floatingLabel=a.first),vD(a=ID())&&(i._notchedOutline=a.first),vD(a=ID())&&(i._lineRipple=a.first);}},hostAttrs:[1,"mat-mdc-form-field"],hostVars:38,hostBindings:function(t,i){t&2&&fh$1("mat-mdc-form-field-label-always-float",i._shouldAlwaysFloat())("mat-mdc-form-field-has-icon-prefix",i._hasIconPrefix)("mat-mdc-form-field-has-icon-suffix",i._hasIconSuffix)("mat-form-field-invalid",i._control.errorState)("mat-form-field-disabled",i._control.disabled)("mat-form-field-autofilled",i._control.autofilled)("mat-form-field-appearance-fill",i.appearance=="fill")("mat-form-field-appearance-outline",i.appearance=="outline")("mat-form-field-hide-placeholder",i._hasFloatingLabel()&&!i._shouldLabelFloat())("mat-primary",i.color!=="accent"&&i.color!=="warn")("mat-accent",i.color==="accent")("mat-warn",i.color==="warn")("ng-untouched",i._shouldForward("untouched"))("ng-touched",i._shouldForward("touched"))("ng-pristine",i._shouldForward("pristine"))("ng-dirty",i._shouldForward("dirty"))("ng-valid",i._shouldForward("valid"))("ng-invalid",i._shouldForward("invalid"))("ng-pending",i._shouldForward("pending"));},inputs:{hideRequiredMarker:"hideRequiredMarker",color:"color",floatLabel:"floatLabel",appearance:"appearance",subscriptSizing:"subscriptSizing",hintLabel:"hintLabel"},exportAs:["matFormField"],features:[JD([{provide:Wt,useExisting:n},{provide:Ts,useExisting:n}])],ngContentSelectors:Sd,decls:18,vars:21,consts:[["labelTemplate",""],["textField",""],["iconPrefixContainer",""],["textPrefixContainer",""],["textSuffixContainer",""],["iconSuffixContainer",""],[1,"mat-mdc-text-field-wrapper","mdc-text-field",3,"click"],[1,"mat-mdc-form-field-focus-overlay"],[1,"mat-mdc-form-field-flex"],["matFormFieldNotchedOutline","",3,"matFormFieldNotchedOutlineOpen"],[1,"mat-mdc-form-field-icon-prefix"],[1,"mat-mdc-form-field-text-prefix"],[1,"mat-mdc-form-field-infix"],[3,"ngTemplateOutlet"],[1,"mat-mdc-form-field-text-suffix"],[1,"mat-mdc-form-field-icon-suffix"],["matFormFieldLineRipple",""],["aria-atomic","true","aria-live","polite",1,"mat-mdc-form-field-subscript-wrapper","mat-mdc-form-field-bottom-align"],[1,"mat-mdc-form-field-error-wrapper"],[1,"mat-mdc-form-field-hint-wrapper"],["matFormFieldFloatingLabel","",3,"floating","monitorResize","id"],["aria-hidden","true",1,"mat-mdc-form-field-required-marker","mdc-floating-label--required"],[3,"id"],[1,"mat-mdc-form-field-hint-spacer"]],template:function(t,i){if(t&1&&(gD(Md),$p(0,Td,1,1,"ng-template",null,0,uw),yi(2,"div",6,1),nh$1("click",function(o){return i._control.onContainerClick(o)}),QE(4,Ad,1,0,"div",7),yi(5,"div",8),QE(6,Od,2,2,"div",9),QE(7,Pd,3,0,"div",10),QE(8,Vd,3,0,"div",11),yi(9,"div",12),QE(10,Nd,1,1,null,13),mD(11),jc$1(),QE(12,Bd,3,0,"div",14),QE(13,zd,3,0,"div",15),jc$1(),QE(14,Hd,1,0,"div",16),jc$1(),yi(15,"div",17),QE(16,jd,2,0,"div",18)(17,Ud,5,1,"div",19),jc$1()),t&2){let a;Wv(2),fh$1("mdc-text-field--filled",!i._hasOutline())("mdc-text-field--outlined",i._hasOutline())("mdc-text-field--no-label",!i._hasFloatingLabel())("mdc-text-field--disabled",i._control.disabled)("mdc-text-field--invalid",i._control.errorState),Wv(2),ZE(!i._hasOutline()&&!i._control.disabled?4:-1),Wv(2),ZE(i._hasOutline()?6:-1),Wv(),ZE(i._hasIconPrefix?7:-1),Wv(),ZE(i._hasTextPrefix?8:-1),Wv(2),ZE(!i._hasOutline()||i._forceDisplayInfixLabel()?10:-1),Wv(2),ZE(i._hasTextSuffix?12:-1),Wv(),ZE(i._hasIconSuffix?13:-1),Wv(),ZE(i._hasOutline()?-1:14),Wv(),fh$1("mat-mdc-form-field-subscript-dynamic-size",i.subscriptSizing==="dynamic");let o=i._getSubscriptMessageType();Wv(),ZE((a=o)==="error"?16:a==="hint"?17:-1);}},dependencies:[ws,Ds,jn$1,Cs,Ma],styles:[`.mdc-text-field {
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
`],encapsulation:2})}return n})();var Zd=["panel"],Jd=["*"];function ec(n,r){if(n&1&&(Vc$1(0,"div",1,0),mD(2),Hc$1()),n&2){let e=r.id,t=pD();RD(t._classList),fh$1("mat-mdc-autocomplete-visible",t.showPanel)("mat-mdc-autocomplete-hidden",!t.showPanel)("mat-autocomplete-panel-animations-enabled",!t._animationsDisabled)("mat-primary",t._color==="primary")("mat-accent",t._color==="accent")("mat-warn",t._color==="warn"),eh$1("id",t.id),Qp("aria-label",t.ariaLabel||null)("aria-labelledby",t._getPanelAriaLabelledby(e));}}var Sa=class{source;option;constructor(r,e){this.source=r,this.option=e;}},As=new x("mat-autocomplete-default-options",{providedIn:"root",factory:()=>({autoActiveFirstOption:false,autoSelectActiveOption:false,hideSingleSelectionIndicator:false,requireSelection:false,hasBackdrop:false})}),c_=(()=>{class n{_changeDetectorRef=T(L0);_elementRef=T(Dr$1);_defaults=T(As);_animationsDisabled=B();_activeOptionChanges=j.EMPTY;_keyManager;showPanel=false;get isOpen(){return this._isOpen&&this.showPanel}_isOpen=false;_latestOpeningTrigger;_setColor(e){this._color=e,this._changeDetectorRef.markForCheck();}_color;template;panel;options;optionGroups;ariaLabel;ariaLabelledby;displayWith=null;autoActiveFirstOption;autoSelectActiveOption;requireSelection;panelWidth;disableRipple=false;optionSelected=new $e;opened=new $e;closed=new $e;optionActivated=new $e;set classList(e){this._classList=e,this._elementRef.nativeElement.className="";}_classList;get hideSingleSelectionIndicator(){return this._hideSingleSelectionIndicator}set hideSingleSelectionIndicator(e){this._hideSingleSelectionIndicator=e,this._syncParentProperties();}_hideSingleSelectionIndicator;_syncParentProperties(){if(this.options)for(let e of this.options)e._changeDetectorRef.markForCheck();}id=T(Y).getId("mat-autocomplete-");inertGroups;constructor(){let e=T(D);this.inertGroups=e?.SAFARI||false,this.autoActiveFirstOption=!!this._defaults.autoActiveFirstOption,this.autoSelectActiveOption=!!this._defaults.autoSelectActiveOption,this.requireSelection=!!this._defaults.requireSelection,this._hideSingleSelectionIndicator=this._defaults.hideSingleSelectionIndicator??false;}ngAfterContentInit(){this._keyManager=new ei(this.options).withWrap().skipPredicate(this._skipPredicate),this._activeOptionChanges=this._keyManager.change.subscribe(e=>{this.isOpen&&this.optionActivated.emit({source:this,option:this.options.toArray()[e]||null});}),this._setVisibility();}ngOnDestroy(){this._keyManager?.destroy(),this._activeOptionChanges.unsubscribe();}_setScrollTop(e){this.panel&&(this.panel.nativeElement.scrollTop=e);}_getScrollTop(){return this.panel?this.panel.nativeElement.scrollTop:0}_setVisibility(){this.showPanel=!!this.options?.length,this._changeDetectorRef.markForCheck();}_emitSelectEvent(e){let t=new Sa(this,e);this.optionSelected.emit(t);}_getPanelAriaLabelledby(e){if(this.ariaLabel)return null;let t=e?e+" ":"";return this.ariaLabelledby?t+this.ariaLabelledby:e}_skipPredicate(){return  false}static \u0275fac=function(t){return new(t||n)};static \u0275cmp=TE({type:n,selectors:[["mat-autocomplete"]],contentQueries:function(t,i,a){if(t&1&&ih$1(a,sr$1,5)(a,Qa$1,5),t&2){let o;vD(o=ID())&&(i.options=o),vD(o=ID())&&(i.optionGroups=o);}},viewQuery:function(t,i){if(t&1&&sh$1(hr$1,7)(Zd,5),t&2){let a;vD(a=ID())&&(i.template=a.first),vD(a=ID())&&(i.panel=a.first);}},hostAttrs:[1,"mat-mdc-autocomplete"],inputs:{ariaLabel:[0,"aria-label","ariaLabel"],ariaLabelledby:[0,"aria-labelledby","ariaLabelledby"],displayWith:"displayWith",autoActiveFirstOption:[2,"autoActiveFirstOption","autoActiveFirstOption",j0],autoSelectActiveOption:[2,"autoSelectActiveOption","autoSelectActiveOption",j0],requireSelection:[2,"requireSelection","requireSelection",j0],panelWidth:"panelWidth",disableRipple:[2,"disableRipple","disableRipple",j0],classList:[0,"class","classList"],hideSingleSelectionIndicator:[2,"hideSingleSelectionIndicator","hideSingleSelectionIndicator",j0]},outputs:{optionSelected:"optionSelected",opened:"opened",closed:"closed",optionActivated:"optionActivated"},exportAs:["matAutocomplete"],features:[JD([{provide:qa$1,useExisting:n}])],ngContentSelectors:Jd,decls:1,vars:0,consts:[["panel",""],["role","listbox",1,"mat-mdc-autocomplete-panel","mdc-menu-surface","mdc-menu-surface--open",3,"id"]],template:function(t,i){t&1&&(gD(),Up(0,ec,3,17,"ng-template"));},styles:[`div.mat-mdc-autocomplete-panel {
  width: 100%;
  max-height: 256px;
  visibility: hidden;
  transform-origin: center top;
  overflow: auto;
  padding: 8px 0;
  box-sizing: border-box;
  position: relative;
  border-radius: var(--mat-autocomplete-container-shape, var(--mat-sys-corner-extra-small));
  box-shadow: var(--mat-autocomplete-container-elevation-shadow, 0px 3px 1px -2px rgba(0, 0, 0, 0.2), 0px 2px 2px 0px rgba(0, 0, 0, 0.14), 0px 1px 5px 0px rgba(0, 0, 0, 0.12));
  background-color: var(--mat-autocomplete-background-color, var(--mat-sys-surface-container));
}
@media (forced-colors: active) {
  div.mat-mdc-autocomplete-panel {
    outline: solid 1px;
  }
}
.cdk-overlay-pane:not(.mat-mdc-autocomplete-panel-above) div.mat-mdc-autocomplete-panel {
  border-top-left-radius: 0;
  border-top-right-radius: 0;
}
.mat-mdc-autocomplete-panel-above div.mat-mdc-autocomplete-panel {
  border-bottom-left-radius: 0;
  border-bottom-right-radius: 0;
  transform-origin: center bottom;
}
div.mat-mdc-autocomplete-panel.mat-mdc-autocomplete-visible {
  visibility: visible;
}

div.mat-mdc-autocomplete-panel.mat-mdc-autocomplete-hidden,
.cdk-overlay-pane:has(> .mat-mdc-autocomplete-hidden) {
  visibility: hidden;
  pointer-events: none;
}

@keyframes _mat-autocomplete-enter {
  from {
    opacity: 0;
    transform: scaleY(0.8);
  }
  to {
    opacity: 1;
    transform: none;
  }
}
.mat-autocomplete-panel-animations-enabled {
  animation: _mat-autocomplete-enter 120ms cubic-bezier(0, 0, 0.2, 1);
}

mat-autocomplete {
  display: none;
}
`],encapsulation:2})}return n})();var tc={provide:Xe,useExisting:vo(()=>nc),multi:true};var ic=new x("mat-autocomplete-scroll-strategy",{providedIn:"root",factory:()=>{let n=T(ge);return ()=>gi$1(n)}}),nc=(()=>{class n{_environmentInjector=T(ae);_element=T(Dr$1);_injector=T(ge);_viewContainerRef=T(Ai$1);_zone=T(Se);_changeDetectorRef=T(L0);_dir=T(Er$2,{optional:true});_formField=T(Wt,{optional:true,host:true});_viewportRuler=T(he);_scrollStrategy=T(ic);_renderer=T(MI);_animationsDisabled=B();_defaults=T(As,{optional:true});_overlayRef=null;_portal;_componentDestroyed=false;_initialized=new ne;_keydownSubscription;_outsideClickSubscription;_cleanupWindowBlur;_previousValue=null;_valueOnAttach=null;_valueOnLastKeydown=null;_positionStrategy;_manuallyFloatingLabel=false;_closingActionsSubscription;_viewportSubscription=j.EMPTY;_breakpointObserver=T(Yt$1);_handsetLandscapeSubscription=j.EMPTY;_canOpenOnNextFocus=true;_valueBeforeAutoSelection;_pendingAutoselectedOption=null;_closeKeyEventStream=new ne;_overlayPanelClass=Ut$1(this._defaults?.overlayPanelClass||[]);_windowBlurHandler=()=>{this._canOpenOnNextFocus=this.panelOpen||!this._hasFocus();};_onChange=()=>{};_onTouched=()=>{};autocomplete;position="auto";connectedTo;autocompleteAttribute="off";autocompleteDisabled=false;_aboveClass="mat-mdc-autocomplete-panel-above";ngAfterViewInit(){this._initialized.next(),this._initialized.complete(),this._cleanupWindowBlur=this._renderer.listen("window","blur",this._windowBlurHandler);}ngOnChanges(e){e.position&&this._positionStrategy&&(this._setStrategyPositions(this._positionStrategy),this.panelOpen&&this._overlayRef.updatePosition());}ngOnDestroy(){this._cleanupWindowBlur?.(),this._handsetLandscapeSubscription.unsubscribe(),this._viewportSubscription.unsubscribe(),this._componentDestroyed=true,this._destroyPanel(),this._closeKeyEventStream.complete();}get panelOpen(){return this._overlayAttached&&this.autocomplete.showPanel}_overlayAttached=false;openPanel(){this._openPanelInternal();}closePanel(){this._resetLabel(),this._overlayAttached&&(this.panelOpen&&this._zone.run(()=>{this.autocomplete.closed.emit();}),this.autocomplete._latestOpeningTrigger===this&&(this.autocomplete._isOpen=false,this.autocomplete._latestOpeningTrigger=null),this._overlayAttached=false,this._pendingAutoselectedOption=null,this._overlayRef&&this._overlayRef.hasAttached()&&(this._overlayRef.detach(),this._closingActionsSubscription.unsubscribe()),this._updatePanelState(),this._componentDestroyed||this._changeDetectorRef.detectChanges());}updatePosition(){this._overlayAttached&&this._overlayRef.updatePosition();}get panelClosingActions(){return kg(this.optionSelections,this.autocomplete._keyManager.tabOut.pipe(en(()=>this._overlayAttached)),this._closeKeyEventStream,this._getOutsideClickStream(),this._overlayRef?this._overlayRef.detachments().pipe(en(()=>this._overlayAttached)):rg()).pipe(Ce(e=>e instanceof ci$1?e:null))}optionSelections=Cg(()=>{let e=this.autocomplete?this.autocomplete.options:null;return e?e.changes.pipe(Hl$1(e),Cl$1(()=>kg(...e.map(t=>t.onSelectionChange)))):this._initialized.pipe(Cl$1(()=>this.optionSelections))});get activeOption(){return this.autocomplete&&this.autocomplete._keyManager?this.autocomplete._keyManager.activeItem:null}_getOutsideClickStream(){return new _(e=>{let t=a=>{let o=H(a),c=this._formField?this._formField.getConnectedOverlayOrigin().nativeElement:null,y=this.connectedTo?this.connectedTo.elementRef.nativeElement:null;this._overlayAttached&&o!==this._element.nativeElement&&!this._hasFocus()&&(!c||!c.contains(o))&&(!y||!y.contains(o))&&this._overlayRef&&!this._overlayRef.overlayElement.contains(o)&&e.next(a);},i=[this._renderer.listen("document","click",t),this._renderer.listen("document","auxclick",t),this._renderer.listen("document","touchend",t)];return ()=>{i.forEach(a=>a());}})}writeValue(e){Promise.resolve(null).then(()=>this._assignOptionValue(e));}registerOnChange(e){this._onChange=e;}registerOnTouched(e){this._onTouched=e;}setDisabledState(e){this._element.nativeElement.disabled=e;}_handleKeydown(e){let t=e,i=t.keyCode,a=$(t);if(i===27&&!a&&t.preventDefault(),this._valueOnLastKeydown=this._element.nativeElement.value,this.activeOption&&i===13&&this.panelOpen&&!a)this.activeOption._selectViaInteraction(),this._resetActiveItem(),t.preventDefault();else if(this.autocomplete){let o=this.autocomplete._keyManager.activeItem,c=i===38||i===40;i===9||c&&!a&&this.panelOpen?this.autocomplete._keyManager.onKeydown(t):c&&this._canOpen()&&this._openPanelInternal(this._valueOnLastKeydown),(c||this.autocomplete._keyManager.activeItem!==o)&&(this._scrollToOption(this.autocomplete._keyManager.activeItemIndex||0),this.autocomplete.autoSelectActiveOption&&this.activeOption&&(this._pendingAutoselectedOption||(this._valueBeforeAutoSelection=this._valueOnLastKeydown),this._pendingAutoselectedOption=this.activeOption,this._assignOptionValue(this.activeOption.value)));}}_handleInput(e){let t=e.target,i=t.value;if(t.type==="number"&&(i=i==""?null:parseFloat(i)),this._previousValue!==i){if(this._previousValue=i,this._pendingAutoselectedOption=null,(!this.autocomplete||!this.autocomplete.requireSelection)&&this._onChange(i),!i)this._clearPreviousSelectedOption(null,false);else if(this.panelOpen&&!this.autocomplete.requireSelection){let a=this.autocomplete.options?.find(o=>o.selected);if(a){let o=this._getDisplayValue(a.value);i!==o&&a.deselect(false);}}if(this._canOpen()&&this._hasFocus()){let a=this._valueOnLastKeydown??this._element.nativeElement.value;this._valueOnLastKeydown=null,this._openPanelInternal(a);}}}_handleFocus(){this._canOpenOnNextFocus?this._canOpen()&&(this._previousValue=this._element.nativeElement.value,this._attachOverlay(this._previousValue),this._floatLabel(true)):this._canOpenOnNextFocus=true;}_handleClick(){this._canOpen()&&!this.panelOpen&&this._openPanelInternal();}_hasFocus(){return jt()===this._element.nativeElement}_floatLabel(e=false){this._formField&&this._formField.floatLabel==="auto"&&(e?this._formField._animateAndLockLabel():this._formField.floatLabel="always",this._manuallyFloatingLabel=true);}_resetLabel(){this._manuallyFloatingLabel&&(this._formField&&(this._formField.floatLabel="auto"),this._manuallyFloatingLabel=false);}_subscribeToClosingActions(){let e=new _(i=>{Dv(()=>{i.next();},{injector:this._environmentInjector});}),t=this.autocomplete.options?.changes.pipe(Ul$1(()=>this._positionStrategy.reapplyLastPosition()),Fl$1(0))??rg();return kg(e,t).pipe(Cl$1(()=>this._zone.run(()=>{let i=this.panelOpen;return this._resetActiveItem(),this._updatePanelState(),this._changeDetectorRef.detectChanges(),this.panelOpen&&this._overlayRef.updatePosition(),i!==this.panelOpen&&(this.panelOpen?this._emitOpened():this.autocomplete.closed.emit()),this.panelClosingActions})),tn(1)).subscribe(i=>this._setValueAndClose(i))}_emitOpened(){this.autocomplete.opened.emit();}_destroyPanel(){this._overlayRef&&(this.closePanel(),this._overlayRef.dispose(),this._overlayRef=null);}_getDisplayValue(e){let t=this.autocomplete;return t&&t.displayWith?t.displayWith(e):e}_assignOptionValue(e){let t=this._getDisplayValue(e);e==null&&this._clearPreviousSelectedOption(null,false),this._updateNativeInputValue(t??"");}_updateNativeInputValue(e){this._formField?this._formField._control.value=e:this._element.nativeElement.value=e,this._previousValue=e;}_setValueAndClose(e){let t=this.autocomplete,i=e?e.source:this._pendingAutoselectedOption;i?(this._clearPreviousSelectedOption(i),this._assignOptionValue(i.value),this._onChange(i.value),t._emitSelectEvent(i),this._element.nativeElement.focus()):t.requireSelection&&this._element.nativeElement.value!==this._valueOnAttach&&(this._clearPreviousSelectedOption(null),this._assignOptionValue(null),this._onChange(null)),this.closePanel();}_clearPreviousSelectedOption(e,t){this.autocomplete?.options?.forEach(i=>{i!==e&&i.selected&&i.deselect(t);});}_openPanelInternal(e=this._element.nativeElement.value){this._attachOverlay(e),this._floatLabel();}_attachOverlay(e){if(!this.autocomplete)return;let t=this._overlayRef;t?(this._positionStrategy.setOrigin(this._getConnectedElement()),t.updateSize({width:this._getPanelWidth()})):(this._portal=new at$1(this.autocomplete.template,this._viewContainerRef,{id:this._formField?.getLabelId()}),t=Zt(this._injector,this._getOverlayConfig()),this._overlayRef=t,this._viewportSubscription=this._viewportRuler.change().subscribe(()=>{this.panelOpen&&t&&t.updateSize({width:this._getPanelWidth()});}),this._handsetLandscapeSubscription=this._breakpointObserver.observe(ft.HandsetLandscape).subscribe(a=>{a.matches?this._positionStrategy.withFlexibleDimensions(true).withGrowAfterOpen(true).withViewportMargin(8):this._positionStrategy.withFlexibleDimensions(false).withGrowAfterOpen(false).withViewportMargin(0);})),t&&!t.hasAttached()&&(t.attach(this._portal),this._valueOnAttach=e,this._valueOnLastKeydown=null,this._closingActionsSubscription=this._subscribeToClosingActions());let i=this.panelOpen;this.autocomplete._isOpen=this._overlayAttached=true,this.autocomplete._latestOpeningTrigger=this,this.autocomplete._setColor(this._formField?.color),this._updatePanelState(),this.panelOpen&&i!==this.panelOpen&&this._emitOpened();}_handlePanelKeydown=e=>{(e.keyCode===27&&!$(e)||e.keyCode===38&&$(e,"altKey"))&&(this._pendingAutoselectedOption&&(this._updateNativeInputValue(this._valueBeforeAutoSelection??""),this._pendingAutoselectedOption=null),this._closeKeyEventStream.next(),this._resetActiveItem(),e.stopPropagation(),e.preventDefault());};_updatePanelState(){if(this.autocomplete._setVisibility(),this.panelOpen){let e=this._overlayRef;this._keydownSubscription||(this._keydownSubscription=e.keydownEvents().subscribe(this._handlePanelKeydown)),this._outsideClickSubscription||(this._outsideClickSubscription=e.outsidePointerEvents().subscribe());}else this._keydownSubscription?.unsubscribe(),this._outsideClickSubscription?.unsubscribe(),this._keydownSubscription=this._outsideClickSubscription=void 0;}_getOverlayConfig(){return new gt({positionStrategy:this._getOverlayPosition(),scrollStrategy:this._scrollStrategy(),width:this._getPanelWidth(),direction:this._dir??void 0,hasBackdrop:this._defaults?.hasBackdrop,backdropClass:this._defaults?.backdropClass||"cdk-overlay-transparent-backdrop",panelClass:this._overlayPanelClass,disableAnimations:this._animationsDisabled})}_getOverlayPosition(){let e=vi(this._injector,this._getConnectedElement()).withFlexibleDimensions(false).withPush(false).withPopoverLocation("inline");return this._setStrategyPositions(e),this._positionStrategy=e,e}_setStrategyPositions(e){let t=[{originX:"start",originY:"bottom",overlayX:"start",overlayY:"top"},{originX:"end",originY:"bottom",overlayX:"end",overlayY:"top"}],i=this._aboveClass,a=[{originX:"start",originY:"top",overlayX:"start",overlayY:"bottom",panelClass:i},{originX:"end",originY:"top",overlayX:"end",overlayY:"bottom",panelClass:i}],o;this.position==="above"?o=a:this.position==="below"?o=t:o=[...t,...a],e.withPositions(o);}_getConnectedElement(){return this.connectedTo?this.connectedTo.elementRef:this._formField?this._formField.getConnectedOverlayOrigin():this._element}_getPanelWidth(){return this.autocomplete.panelWidth||this._getHostWidth()}_getHostWidth(){return this._getConnectedElement().nativeElement.getBoundingClientRect().width}_resetActiveItem(){let e=this.autocomplete;if(e.autoActiveFirstOption){let t=-1;for(let i=0;i<e.options.length;i++)if(!e.options.get(i).disabled){t=i;break}e._keyManager.setActiveItem(t);}else e._keyManager.setActiveItem(-1);}_canOpen(){let e=this._element.nativeElement;return !e.readOnly&&!e.disabled&&!this.autocompleteDisabled}_scrollToOption(e){let t=this.autocomplete,i=Zm$1(e,t.options,t.optionGroups);if(e===0&&i===1)t._setScrollTop(0);else if(t.panel){let a=t.options.toArray()[e];if(a){let o=a._getHostElement(),c=Km$1(o.offsetTop,o.offsetHeight,t._getScrollTop(),t.panel.nativeElement.offsetHeight);t._setScrollTop(c);}}}static \u0275fac=function(t){return new(t||n)};static \u0275dir=SE({type:n,selectors:[["input","matAutocomplete",""],["textarea","matAutocomplete",""]],hostAttrs:[1,"mat-mdc-autocomplete-trigger"],hostVars:7,hostBindings:function(t,i){t&1&&nh$1("focusin",function(){return i._handleFocus()})("blur",function(){return i._onTouched()})("input",function(o){return i._handleInput(o)})("keydown",function(o){return i._handleKeydown(o)})("click",function(){return i._handleClick()}),t&2&&Qp("autocomplete",i.autocompleteAttribute)("role",i.autocompleteDisabled?null:"combobox")("aria-autocomplete",i.autocompleteDisabled?null:"list")("aria-activedescendant",i.panelOpen&&i.activeOption?i.activeOption.id:null)("aria-expanded",i.autocompleteDisabled?null:i.panelOpen.toString())("aria-controls",i.autocompleteDisabled||!i.panelOpen?null:i.autocomplete?.id)("aria-haspopup",i.autocompleteDisabled?null:"listbox");},inputs:{autocomplete:[0,"matAutocomplete","autocomplete"],position:[0,"matAutocompletePosition","position"],connectedTo:[0,"matAutocompleteConnectedTo","connectedTo"],autocompleteAttribute:[0,"autocomplete","autocompleteAttribute"],autocompleteDisabled:[2,"matAutocompleteDisabled","autocompleteDisabled",j0]},exportAs:["matAutocompleteTrigger"],features:[JD([tc]),zm$1]})}return n})(),Ea=(()=>{class n{static \u0275fac=function(t){return new(t||n)};static \u0275mod=bE({type:n});static \u0275inj=cu$1({imports:[Kt$1,js$1,di,js$1,jo$1]})}return n})();var ac=["input"],rc=["label"],oc=["*"],Ta={color:"accent",clickAction:"check-indeterminate",disabledInteractive:false},sc=new x("mat-checkbox-default-options",{providedIn:"root",factory:()=>Ta}),Ue=(function(n){return n[n.Init=0]="Init",n[n.Checked=1]="Checked",n[n.Unchecked=2]="Unchecked",n[n.Indeterminate=3]="Indeterminate",n})(Ue||{}),Aa=class{source;checked},lc=(()=>{class n{_elementRef=T(Dr$1);_changeDetectorRef=T(L0);_ngZone=T(Se);_animationsDisabled=B();_options=T(sc,{optional:true});focus(){this._inputElement.nativeElement.focus();}_createChangeEvent(e){let t=new Aa;return t.source=this,t.checked=e,t}_getAnimationTargetElement(){return this._inputElement?.nativeElement}_animationClasses={uncheckedToChecked:"mdc-checkbox--anim-unchecked-checked",uncheckedToIndeterminate:"mdc-checkbox--anim-unchecked-indeterminate",checkedToUnchecked:"mdc-checkbox--anim-checked-unchecked",checkedToIndeterminate:"mdc-checkbox--anim-checked-indeterminate",indeterminateToChecked:"mdc-checkbox--anim-indeterminate-checked",indeterminateToUnchecked:"mdc-checkbox--anim-indeterminate-unchecked"};ariaLabel="";ariaLabelledby=null;ariaDescribedby;ariaExpanded;ariaControls;ariaOwns;_uniqueId;id;get inputId(){return `${this.id||this._uniqueId}-input`}required=false;labelPosition="after";name=null;change=new $e;indeterminateChange=new $e;value;disableRipple=false;_inputElement;_labelElement;tabIndex;color;disabledInteractive;_onTouched=()=>{};_currentAnimationClass="";_currentCheckState=Ue.Init;_controlValueAccessorChangeFn=()=>{};_validatorChangeFn=()=>{};constructor(){T(W$1).load(bn$1);let e=T(new Ah$1("tabindex"),{optional:true});this._options=this._options||Ta,this.color=this._options.color||Ta.color,this.tabIndex=e==null?0:parseInt(e)||0,this.id=this._uniqueId=T(Y).getId("mat-mdc-checkbox-"),this.disabledInteractive=this._options?.disabledInteractive??false;}ngOnChanges(e){e.required&&this._validatorChangeFn();}ngAfterViewInit(){this._syncIndeterminate(this.indeterminate);}get checked(){return this._checked}set checked(e){e!=this.checked&&(this._checked=e,this._changeDetectorRef.markForCheck());}_checked=false;get disabled(){return this._disabled}set disabled(e){e!==this.disabled&&(this._disabled=e,this._changeDetectorRef.markForCheck());}_disabled=false;get indeterminate(){return this._indeterminate()}set indeterminate(e){let t=e!=this._indeterminate();this._indeterminate.set(e),t&&(e?this._transitionCheckState(Ue.Indeterminate):this._transitionCheckState(this.checked?Ue.Checked:Ue.Unchecked),this.indeterminateChange.emit(e)),this._syncIndeterminate(e);}_indeterminate=Vo$1(false);_isRippleDisabled(){return this.disableRipple||this.disabled}_onLabelTextChange(){this._changeDetectorRef.detectChanges();}writeValue(e){this.checked=!!e;}registerOnChange(e){this._controlValueAccessorChangeFn=e;}registerOnTouched(e){this._onTouched=e;}setDisabledState(e){this.disabled=e;}validate(e){return this.required&&e.value!==true?{required:true}:null}registerOnValidatorChange(e){this._validatorChangeFn=e;}_transitionCheckState(e){let t=this._currentCheckState,i=this._getAnimationTargetElement();if(!(t===e||!i)&&(this._currentAnimationClass&&i.classList.remove(this._currentAnimationClass),this._currentAnimationClass=this._getAnimationClassForCheckStateTransition(t,e),this._currentCheckState=e,this._currentAnimationClass.length>0)){i.classList.add(this._currentAnimationClass);let a=this._currentAnimationClass;this._ngZone.runOutsideAngular(()=>{setTimeout(()=>{i.classList.remove(a);},1e3);});}}_emitChangeEvent(){this._controlValueAccessorChangeFn(this.checked),this.change.emit(this._createChangeEvent(this.checked)),this._inputElement&&(this._inputElement.nativeElement.checked=this.checked);}toggle(){this.checked=!this.checked,this._controlValueAccessorChangeFn(this.checked);}_handleInputClick(){let e=this._options?.clickAction;!this.disabled&&e!=="noop"?(this.indeterminate&&e!=="check"&&Promise.resolve().then(()=>{this._indeterminate.set(false),this.indeterminateChange.emit(false);}),this._checked=!this._checked,this._transitionCheckState(this._checked?Ue.Checked:Ue.Unchecked),this._emitChangeEvent()):(this.disabled&&this.disabledInteractive||!this.disabled&&e==="noop")&&(this._inputElement.nativeElement.checked=this.checked,this._inputElement.nativeElement.indeterminate=this.indeterminate);}_onInteractionEvent(e){e.stopPropagation();}_onBlur(){Promise.resolve().then(()=>{this._onTouched(),this._changeDetectorRef.markForCheck();});}_getAnimationClassForCheckStateTransition(e,t){if(this._animationsDisabled)return "";switch(e){case Ue.Init:if(t===Ue.Checked)return this._animationClasses.uncheckedToChecked;if(t==Ue.Indeterminate)return this._checked?this._animationClasses.checkedToIndeterminate:this._animationClasses.uncheckedToIndeterminate;break;case Ue.Unchecked:return t===Ue.Checked?this._animationClasses.uncheckedToChecked:this._animationClasses.uncheckedToIndeterminate;case Ue.Checked:return t===Ue.Unchecked?this._animationClasses.checkedToUnchecked:this._animationClasses.checkedToIndeterminate;case Ue.Indeterminate:return t===Ue.Checked?this._animationClasses.indeterminateToChecked:this._animationClasses.indeterminateToUnchecked}return ""}_syncIndeterminate(e){let t=this._inputElement;t&&(t.nativeElement.indeterminate=e);}_onInputClick(){this._handleInputClick();}_onTouchTargetClick(){this._handleInputClick(),this.disabled||this._inputElement.nativeElement.focus();}_preventBubblingFromLabel(e){e.target&&this._labelElement.nativeElement.contains(e.target)&&e.stopPropagation();}static \u0275fac=function(t){return new(t||n)};static \u0275cmp=TE({type:n,selectors:[["mat-checkbox"]],viewQuery:function(t,i){if(t&1&&sh$1(ac,5)(rc,5),t&2){let a;vD(a=ID())&&(i._inputElement=a.first),vD(a=ID())&&(i._labelElement=a.first);}},hostAttrs:[1,"mat-mdc-checkbox"],hostVars:16,hostBindings:function(t,i){t&2&&(eh$1("id",i.id),Qp("tabindex",null)("aria-label",null)("aria-labelledby",null),RD(i.color?"mat-"+i.color:"mat-accent"),fh$1("_mat-animation-noopable",i._animationsDisabled)("mdc-checkbox--disabled",i.disabled)("mat-mdc-checkbox-disabled",i.disabled)("mat-mdc-checkbox-checked",i.checked)("mat-mdc-checkbox-disabled-interactive",i.disabledInteractive));},inputs:{ariaLabel:[0,"aria-label","ariaLabel"],ariaLabelledby:[0,"aria-labelledby","ariaLabelledby"],ariaDescribedby:[0,"aria-describedby","ariaDescribedby"],ariaExpanded:[2,"aria-expanded","ariaExpanded",j0],ariaControls:[0,"aria-controls","ariaControls"],ariaOwns:[0,"aria-owns","ariaOwns"],id:"id",required:[2,"required","required",j0],labelPosition:"labelPosition",name:"name",value:"value",disableRipple:[2,"disableRipple","disableRipple",j0],tabIndex:[2,"tabIndex","tabIndex",e=>e==null?void 0:V0(e)],color:"color",disabledInteractive:[2,"disabledInteractive","disabledInteractive",j0],checked:[2,"checked","checked",j0],disabled:[2,"disabled","disabled",j0],indeterminate:[2,"indeterminate","indeterminate",j0]},outputs:{change:"change",indeterminateChange:"indeterminateChange"},exportAs:["matCheckbox"],features:[JD([{provide:Xe,useExisting:vo(()=>n),multi:true},{provide:Ze,useExisting:n,multi:true}]),zm$1],ngContentSelectors:oc,decls:15,vars:23,consts:[["checkbox",""],["input",""],["label",""],["mat-internal-form-field","",3,"click","labelPosition"],[1,"mdc-checkbox"],["aria-hidden","true",1,"mat-mdc-checkbox-touch-target",3,"click"],["type","checkbox",1,"mdc-checkbox__native-control",3,"blur","click","change","checked","indeterminate","disabled","id","required","tabIndex"],["aria-hidden","true",1,"mdc-checkbox__ripple"],["aria-hidden","true",1,"mdc-checkbox__background"],["focusable","false","viewBox","0 0 24 24",1,"mdc-checkbox__checkmark"],["fill","none","d","M1.73,12.91 8.1,19.28 22.79,4.59",1,"mdc-checkbox__checkmark-path"],[1,"mdc-checkbox__mixedmark"],["mat-ripple","","aria-hidden","true",1,"mat-mdc-checkbox-ripple","mat-focus-indicator",3,"matRippleTrigger","matRippleDisabled","matRippleCentered"],[1,"mdc-label",3,"for"]],template:function(t,i){if(t&1&&(gD(),yi(0,"div",3),nh$1("click",function(o){return i._preventBubblingFromLabel(o)}),yi(1,"div",4,0)(3,"div",5),nh$1("click",function(){return i._onTouchTargetClick()}),jc$1(),yi(4,"input",6,1),nh$1("blur",function(){return i._onBlur()})("click",function(){return i._onInputClick()})("change",function(o){return i._onInteractionEvent(o)}),jc$1(),Yp(6,"div",7),yi(7,"div",8),Gu(),yi(8,"svg",9),Yp(9,"path",10),jc$1(),zu(),Yp(10,"div",11),jc$1(),Yp(11,"div",12),jc$1(),yi(12,"label",13,2),mD(14),jc$1()()),t&2){let a=DD(2);Zp("labelPosition",i.labelPosition),Wv(4),fh$1("mdc-checkbox--selected",i.checked),Zp("checked",i.checked)("indeterminate",i.indeterminate)("disabled",i.disabled&&!i.disabledInteractive)("id",i.inputId)("required",i.required)("tabIndex",i.disabled&&!i.disabledInteractive?-1:i.tabIndex),Qp("aria-label",i.ariaLabel||null)("aria-labelledby",i.ariaLabelledby)("aria-describedby",i.ariaDescribedby)("aria-checked",i.indeterminate?"mixed":null)("aria-controls",i.ariaControls)("aria-disabled",i.disabled&&i.disabledInteractive?true:null)("aria-expanded",i.ariaExpanded)("aria-owns",i.ariaOwns)("name",i.name)("value",i.value),Wv(7),Zp("matRippleTrigger",a)("matRippleDisabled",i.disableRipple||i.disabled)("matRippleCentered",true),Wv(),Zp("for",i.inputId);}},dependencies:[rr$1,bf],styles:[`.mdc-checkbox {
  display: inline-block;
  position: relative;
  flex: 0 0 18px;
  box-sizing: content-box;
  width: 18px;
  height: 18px;
  line-height: 0;
  white-space: nowrap;
  cursor: pointer;
  vertical-align: bottom;
  padding: calc((var(--mat-checkbox-state-layer-size, 40px) - 18px) / 2);
  margin: calc((var(--mat-checkbox-state-layer-size, 40px) - var(--mat-checkbox-state-layer-size, 40px)) / 2);
}
.mdc-checkbox:hover > .mdc-checkbox__ripple {
  opacity: var(--mat-checkbox-unselected-hover-state-layer-opacity, var(--mat-sys-hover-state-layer-opacity));
  background-color: var(--mat-checkbox-unselected-hover-state-layer-color, var(--mat-sys-on-surface));
}
.mdc-checkbox:hover > .mat-mdc-checkbox-ripple > .mat-ripple-element {
  background-color: var(--mat-checkbox-unselected-hover-state-layer-color, var(--mat-sys-on-surface));
}
.mdc-checkbox .mdc-checkbox__native-control:focus + .mdc-checkbox__ripple {
  opacity: var(--mat-checkbox-unselected-focus-state-layer-opacity, var(--mat-sys-focus-state-layer-opacity));
  background-color: var(--mat-checkbox-unselected-focus-state-layer-color, var(--mat-sys-on-surface));
}
.mdc-checkbox .mdc-checkbox__native-control:focus ~ .mat-mdc-checkbox-ripple .mat-ripple-element {
  background-color: var(--mat-checkbox-unselected-focus-state-layer-color, var(--mat-sys-on-surface));
}
.mdc-checkbox:active > .mdc-checkbox__native-control + .mdc-checkbox__ripple {
  opacity: var(--mat-checkbox-unselected-pressed-state-layer-opacity, var(--mat-sys-pressed-state-layer-opacity));
  background-color: var(--mat-checkbox-unselected-pressed-state-layer-color, var(--mat-sys-primary));
}
.mdc-checkbox:active > .mdc-checkbox__native-control ~ .mat-mdc-checkbox-ripple .mat-ripple-element {
  background-color: var(--mat-checkbox-unselected-pressed-state-layer-color, var(--mat-sys-primary));
}
.mdc-checkbox:hover > .mdc-checkbox__native-control:checked + .mdc-checkbox__ripple {
  opacity: var(--mat-checkbox-selected-hover-state-layer-opacity, var(--mat-sys-hover-state-layer-opacity));
  background-color: var(--mat-checkbox-selected-hover-state-layer-color, var(--mat-sys-primary));
}
.mdc-checkbox:hover > .mdc-checkbox__native-control:checked ~ .mat-mdc-checkbox-ripple .mat-ripple-element {
  background-color: var(--mat-checkbox-selected-hover-state-layer-color, var(--mat-sys-primary));
}
.mdc-checkbox .mdc-checkbox__native-control:focus:checked + .mdc-checkbox__ripple {
  opacity: var(--mat-checkbox-selected-focus-state-layer-opacity, var(--mat-sys-focus-state-layer-opacity));
  background-color: var(--mat-checkbox-selected-focus-state-layer-color, var(--mat-sys-primary));
}
.mdc-checkbox .mdc-checkbox__native-control:focus:checked ~ .mat-mdc-checkbox-ripple .mat-ripple-element {
  background-color: var(--mat-checkbox-selected-focus-state-layer-color, var(--mat-sys-primary));
}
.mdc-checkbox:active > .mdc-checkbox__native-control:checked + .mdc-checkbox__ripple {
  opacity: var(--mat-checkbox-selected-pressed-state-layer-opacity, var(--mat-sys-pressed-state-layer-opacity));
  background-color: var(--mat-checkbox-selected-pressed-state-layer-color, var(--mat-sys-on-surface));
}
.mdc-checkbox:active > .mdc-checkbox__native-control:checked ~ .mat-mdc-checkbox-ripple .mat-ripple-element {
  background-color: var(--mat-checkbox-selected-pressed-state-layer-color, var(--mat-sys-on-surface));
}
.mdc-checkbox--disabled.mat-mdc-checkbox-disabled-interactive .mdc-checkbox .mdc-checkbox__native-control ~ .mat-mdc-checkbox-ripple .mat-ripple-element,
.mdc-checkbox--disabled.mat-mdc-checkbox-disabled-interactive .mdc-checkbox .mdc-checkbox__native-control + .mdc-checkbox__ripple {
  background-color: var(--mat-checkbox-unselected-hover-state-layer-color, var(--mat-sys-on-surface));
}
.mdc-checkbox .mdc-checkbox__native-control {
  position: absolute;
  margin: 0;
  padding: 0;
  opacity: 0;
  cursor: inherit;
  z-index: 1;
  width: var(--mat-checkbox-state-layer-size, 40px);
  height: var(--mat-checkbox-state-layer-size, 40px);
  top: calc((var(--mat-checkbox-state-layer-size, 40px) - var(--mat-checkbox-state-layer-size, 40px)) / 2);
  right: calc((var(--mat-checkbox-state-layer-size, 40px) - var(--mat-checkbox-state-layer-size, 40px)) / 2);
  left: calc((var(--mat-checkbox-state-layer-size, 40px) - var(--mat-checkbox-state-layer-size, 40px)) / 2);
}

.mdc-checkbox--disabled {
  cursor: default;
  pointer-events: none;
}

.mdc-checkbox__background {
  display: inline-flex;
  position: absolute;
  align-items: center;
  justify-content: center;
  box-sizing: border-box;
  width: 18px;
  height: 18px;
  border: 2px solid currentColor;
  border-radius: 2px;
  background-color: transparent;
  pointer-events: none;
  will-change: background-color, border-color;
  transition: background-color 90ms cubic-bezier(0.4, 0, 0.6, 1), border-color 90ms cubic-bezier(0.4, 0, 0.6, 1);
  -webkit-print-color-adjust: exact;
  color-adjust: exact;
  border-color: var(--mat-checkbox-unselected-icon-color, var(--mat-sys-on-surface-variant));
  top: calc((var(--mat-checkbox-state-layer-size, 40px) - 18px) / 2);
  left: calc((var(--mat-checkbox-state-layer-size, 40px) - 18px) / 2);
}

.mdc-checkbox__native-control:enabled:checked ~ .mdc-checkbox__background,
.mdc-checkbox__native-control:enabled:indeterminate ~ .mdc-checkbox__background {
  border-color: var(--mat-checkbox-selected-icon-color, var(--mat-sys-primary));
  background-color: var(--mat-checkbox-selected-icon-color, var(--mat-sys-primary));
}

.mdc-checkbox--disabled .mdc-checkbox__background {
  border-color: var(--mat-checkbox-disabled-unselected-icon-color, color-mix(in srgb, var(--mat-sys-on-surface) 38%, transparent));
}
@media (forced-colors: active) {
  .mdc-checkbox--disabled .mdc-checkbox__background {
    border-color: GrayText;
  }
}

.mdc-checkbox__native-control:disabled:checked ~ .mdc-checkbox__background,
.mdc-checkbox__native-control:disabled:indeterminate ~ .mdc-checkbox__background {
  background-color: var(--mat-checkbox-disabled-selected-icon-color, color-mix(in srgb, var(--mat-sys-on-surface) 38%, transparent));
  border-color: transparent;
}
@media (forced-colors: active) {
  .mdc-checkbox__native-control:disabled:checked ~ .mdc-checkbox__background,
  .mdc-checkbox__native-control:disabled:indeterminate ~ .mdc-checkbox__background {
    border-color: GrayText;
  }
}

.mdc-checkbox:hover > .mdc-checkbox__native-control:not(:checked) ~ .mdc-checkbox__background,
.mdc-checkbox:hover > .mdc-checkbox__native-control:not(:indeterminate) ~ .mdc-checkbox__background {
  border-color: var(--mat-checkbox-unselected-hover-icon-color, var(--mat-sys-on-surface));
  background-color: transparent;
}

.mdc-checkbox:hover > .mdc-checkbox__native-control:checked ~ .mdc-checkbox__background,
.mdc-checkbox:hover > .mdc-checkbox__native-control:indeterminate ~ .mdc-checkbox__background {
  border-color: var(--mat-checkbox-selected-hover-icon-color, var(--mat-sys-primary));
  background-color: var(--mat-checkbox-selected-hover-icon-color, var(--mat-sys-primary));
}

.mdc-checkbox__native-control:focus:focus:not(:checked) ~ .mdc-checkbox__background,
.mdc-checkbox__native-control:focus:focus:not(:indeterminate) ~ .mdc-checkbox__background {
  border-color: var(--mat-checkbox-unselected-focus-icon-color, var(--mat-sys-on-surface));
}

.mdc-checkbox__native-control:focus:focus:checked ~ .mdc-checkbox__background,
.mdc-checkbox__native-control:focus:focus:indeterminate ~ .mdc-checkbox__background {
  border-color: var(--mat-checkbox-selected-focus-icon-color, var(--mat-sys-primary));
  background-color: var(--mat-checkbox-selected-focus-icon-color, var(--mat-sys-primary));
}

.mdc-checkbox--disabled.mat-mdc-checkbox-disabled-interactive .mdc-checkbox:hover > .mdc-checkbox__native-control ~ .mdc-checkbox__background,
.mdc-checkbox--disabled.mat-mdc-checkbox-disabled-interactive .mdc-checkbox .mdc-checkbox__native-control:focus ~ .mdc-checkbox__background,
.mdc-checkbox--disabled.mat-mdc-checkbox-disabled-interactive .mdc-checkbox__background {
  border-color: var(--mat-checkbox-disabled-unselected-icon-color, color-mix(in srgb, var(--mat-sys-on-surface) 38%, transparent));
}
@media (forced-colors: active) {
  .mdc-checkbox--disabled.mat-mdc-checkbox-disabled-interactive .mdc-checkbox:hover > .mdc-checkbox__native-control ~ .mdc-checkbox__background,
  .mdc-checkbox--disabled.mat-mdc-checkbox-disabled-interactive .mdc-checkbox .mdc-checkbox__native-control:focus ~ .mdc-checkbox__background,
  .mdc-checkbox--disabled.mat-mdc-checkbox-disabled-interactive .mdc-checkbox__background {
    border-color: GrayText;
  }
}
.mdc-checkbox--disabled.mat-mdc-checkbox-disabled-interactive .mdc-checkbox__native-control:checked ~ .mdc-checkbox__background,
.mdc-checkbox--disabled.mat-mdc-checkbox-disabled-interactive .mdc-checkbox__native-control:indeterminate ~ .mdc-checkbox__background {
  background-color: var(--mat-checkbox-disabled-selected-icon-color, color-mix(in srgb, var(--mat-sys-on-surface) 38%, transparent));
  border-color: transparent;
}

.mdc-checkbox__checkmark {
  position: absolute;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  width: 100%;
  opacity: 0;
  transition: opacity 180ms cubic-bezier(0.4, 0, 0.6, 1);
  color: var(--mat-checkbox-selected-checkmark-color, var(--mat-sys-on-primary));
}
@media (forced-colors: active) {
  .mdc-checkbox__checkmark {
    color: CanvasText;
  }
}

.mdc-checkbox--disabled .mdc-checkbox__checkmark, .mdc-checkbox--disabled.mat-mdc-checkbox-disabled-interactive .mdc-checkbox__checkmark {
  color: var(--mat-checkbox-disabled-selected-checkmark-color, var(--mat-sys-surface));
}
@media (forced-colors: active) {
  .mdc-checkbox--disabled .mdc-checkbox__checkmark, .mdc-checkbox--disabled.mat-mdc-checkbox-disabled-interactive .mdc-checkbox__checkmark {
    color: GrayText;
  }
}

.mdc-checkbox__checkmark-path {
  transition: stroke-dashoffset 180ms cubic-bezier(0.4, 0, 0.6, 1);
  stroke: currentColor;
  stroke-width: 3.12px;
  stroke-dashoffset: 29.7833385;
  stroke-dasharray: 29.7833385;
}

.mdc-checkbox__mixedmark {
  width: 100%;
  height: 0;
  transform: scaleX(0) rotate(0deg);
  border-width: 1px;
  border-style: solid;
  opacity: 0;
  transition: opacity 90ms cubic-bezier(0.4, 0, 0.6, 1), transform 90ms cubic-bezier(0.4, 0, 0.6, 1);
  border-color: var(--mat-checkbox-selected-checkmark-color, var(--mat-sys-on-primary));
}
@media (forced-colors: active) {
  .mdc-checkbox__mixedmark {
    margin: 0 1px;
  }
}

.mdc-checkbox--disabled .mdc-checkbox__mixedmark, .mdc-checkbox--disabled.mat-mdc-checkbox-disabled-interactive .mdc-checkbox__mixedmark {
  border-color: var(--mat-checkbox-disabled-selected-checkmark-color, var(--mat-sys-surface));
}
@media (forced-colors: active) {
  .mdc-checkbox--disabled .mdc-checkbox__mixedmark, .mdc-checkbox--disabled.mat-mdc-checkbox-disabled-interactive .mdc-checkbox__mixedmark {
    border-color: GrayText;
  }
}

.mdc-checkbox--anim-unchecked-checked .mdc-checkbox__background,
.mdc-checkbox--anim-unchecked-indeterminate .mdc-checkbox__background,
.mdc-checkbox--anim-checked-unchecked .mdc-checkbox__background,
.mdc-checkbox--anim-indeterminate-unchecked .mdc-checkbox__background {
  animation-duration: 180ms;
  animation-timing-function: linear;
}

.mdc-checkbox--anim-unchecked-checked .mdc-checkbox__checkmark-path {
  animation: mdc-checkbox-unchecked-checked-checkmark-path 180ms linear;
  transition: none;
}

.mdc-checkbox--anim-unchecked-indeterminate .mdc-checkbox__mixedmark {
  animation: mdc-checkbox-unchecked-indeterminate-mixedmark 90ms linear;
  transition: none;
}

.mdc-checkbox--anim-checked-unchecked .mdc-checkbox__checkmark-path {
  animation: mdc-checkbox-checked-unchecked-checkmark-path 90ms linear;
  transition: none;
}

.mdc-checkbox--anim-checked-indeterminate .mdc-checkbox__checkmark {
  animation: mdc-checkbox-checked-indeterminate-checkmark 90ms linear;
  transition: none;
}
.mdc-checkbox--anim-checked-indeterminate .mdc-checkbox__mixedmark {
  animation: mdc-checkbox-checked-indeterminate-mixedmark 90ms linear;
  transition: none;
}

.mdc-checkbox--anim-indeterminate-checked .mdc-checkbox__checkmark {
  animation: mdc-checkbox-indeterminate-checked-checkmark 500ms linear;
  transition: none;
}
.mdc-checkbox--anim-indeterminate-checked .mdc-checkbox__mixedmark {
  animation: mdc-checkbox-indeterminate-checked-mixedmark 500ms linear;
  transition: none;
}

.mdc-checkbox--anim-indeterminate-unchecked .mdc-checkbox__mixedmark {
  animation: mdc-checkbox-indeterminate-unchecked-mixedmark 300ms linear;
  transition: none;
}

.mdc-checkbox__native-control:checked ~ .mdc-checkbox__background,
.mdc-checkbox__native-control:indeterminate ~ .mdc-checkbox__background {
  transition: border-color 90ms cubic-bezier(0, 0, 0.2, 1), background-color 90ms cubic-bezier(0, 0, 0.2, 1);
}
.mdc-checkbox__native-control:checked ~ .mdc-checkbox__background > .mdc-checkbox__checkmark > .mdc-checkbox__checkmark-path,
.mdc-checkbox__native-control:indeterminate ~ .mdc-checkbox__background > .mdc-checkbox__checkmark > .mdc-checkbox__checkmark-path {
  stroke-dashoffset: 0;
}

.mdc-checkbox__native-control:checked ~ .mdc-checkbox__background > .mdc-checkbox__checkmark {
  transition: opacity 180ms cubic-bezier(0, 0, 0.2, 1), transform 180ms cubic-bezier(0, 0, 0.2, 1);
  opacity: 1;
}
.mdc-checkbox__native-control:checked ~ .mdc-checkbox__background > .mdc-checkbox__mixedmark {
  transform: scaleX(1) rotate(-45deg);
}

.mdc-checkbox__native-control:indeterminate ~ .mdc-checkbox__background > .mdc-checkbox__checkmark {
  transform: rotate(45deg);
  opacity: 0;
  transition: opacity 90ms cubic-bezier(0.4, 0, 0.6, 1), transform 90ms cubic-bezier(0.4, 0, 0.6, 1);
}
.mdc-checkbox__native-control:indeterminate ~ .mdc-checkbox__background > .mdc-checkbox__mixedmark {
  transform: scaleX(1) rotate(0deg);
  opacity: 1;
}

@keyframes mdc-checkbox-unchecked-checked-checkmark-path {
  0%, 50% {
    stroke-dashoffset: 29.7833385;
  }
  50% {
    animation-timing-function: cubic-bezier(0, 0, 0.2, 1);
  }
  100% {
    stroke-dashoffset: 0;
  }
}
@keyframes mdc-checkbox-unchecked-indeterminate-mixedmark {
  0%, 68.2% {
    transform: scaleX(0);
  }
  68.2% {
    animation-timing-function: cubic-bezier(0, 0, 0, 1);
  }
  100% {
    transform: scaleX(1);
  }
}
@keyframes mdc-checkbox-checked-unchecked-checkmark-path {
  from {
    animation-timing-function: cubic-bezier(0.4, 0, 1, 1);
    opacity: 1;
    stroke-dashoffset: 0;
  }
  to {
    opacity: 0;
    stroke-dashoffset: -29.7833385;
  }
}
@keyframes mdc-checkbox-checked-indeterminate-checkmark {
  from {
    animation-timing-function: cubic-bezier(0, 0, 0.2, 1);
    transform: rotate(0deg);
    opacity: 1;
  }
  to {
    transform: rotate(45deg);
    opacity: 0;
  }
}
@keyframes mdc-checkbox-indeterminate-checked-checkmark {
  from {
    animation-timing-function: cubic-bezier(0.14, 0, 0, 1);
    transform: rotate(45deg);
    opacity: 0;
  }
  to {
    transform: rotate(360deg);
    opacity: 1;
  }
}
@keyframes mdc-checkbox-checked-indeterminate-mixedmark {
  from {
    animation-timing-function: cubic-bezier(0, 0, 0.2, 1);
    transform: rotate(-45deg);
    opacity: 0;
  }
  to {
    transform: rotate(0deg);
    opacity: 1;
  }
}
@keyframes mdc-checkbox-indeterminate-checked-mixedmark {
  from {
    animation-timing-function: cubic-bezier(0.14, 0, 0, 1);
    transform: rotate(0deg);
    opacity: 1;
  }
  to {
    transform: rotate(315deg);
    opacity: 0;
  }
}
@keyframes mdc-checkbox-indeterminate-unchecked-mixedmark {
  0% {
    animation-timing-function: linear;
    transform: scaleX(1);
    opacity: 1;
  }
  32.8%, 100% {
    transform: scaleX(0);
    opacity: 0;
  }
}
.mat-mdc-checkbox {
  display: inline-block;
  position: relative;
  -webkit-tap-highlight-color: transparent;
}
.mat-mdc-checkbox._mat-animation-noopable > .mat-internal-form-field > .mdc-checkbox > .mat-mdc-checkbox-touch-target,
.mat-mdc-checkbox._mat-animation-noopable > .mat-internal-form-field > .mdc-checkbox > .mdc-checkbox__native-control,
.mat-mdc-checkbox._mat-animation-noopable > .mat-internal-form-field > .mdc-checkbox > .mdc-checkbox__ripple,
.mat-mdc-checkbox._mat-animation-noopable > .mat-internal-form-field > .mdc-checkbox > .mat-mdc-checkbox-ripple::before,
.mat-mdc-checkbox._mat-animation-noopable > .mat-internal-form-field > .mdc-checkbox > .mdc-checkbox__background,
.mat-mdc-checkbox._mat-animation-noopable > .mat-internal-form-field > .mdc-checkbox > .mdc-checkbox__background > .mdc-checkbox__checkmark,
.mat-mdc-checkbox._mat-animation-noopable > .mat-internal-form-field > .mdc-checkbox > .mdc-checkbox__background > .mdc-checkbox__checkmark > .mdc-checkbox__checkmark-path,
.mat-mdc-checkbox._mat-animation-noopable > .mat-internal-form-field > .mdc-checkbox > .mdc-checkbox__background > .mdc-checkbox__mixedmark {
  transition: none !important;
  animation: none !important;
}
.mat-mdc-checkbox label {
  cursor: pointer;
}
.mat-mdc-checkbox .mat-internal-form-field {
  color: var(--mat-checkbox-label-text-color, var(--mat-sys-on-surface));
  font-family: var(--mat-checkbox-label-text-font, var(--mat-sys-body-medium-font));
  line-height: var(--mat-checkbox-label-text-line-height, var(--mat-sys-body-medium-line-height));
  font-size: var(--mat-checkbox-label-text-size, var(--mat-sys-body-medium-size));
  letter-spacing: var(--mat-checkbox-label-text-tracking, var(--mat-sys-body-medium-tracking));
  font-weight: var(--mat-checkbox-label-text-weight, var(--mat-sys-body-medium-weight));
}
.mat-mdc-checkbox.mat-mdc-checkbox-disabled.mat-mdc-checkbox-disabled-interactive {
  pointer-events: auto;
}
.mat-mdc-checkbox.mat-mdc-checkbox-disabled.mat-mdc-checkbox-disabled-interactive input {
  cursor: default;
}
.mat-mdc-checkbox.mat-mdc-checkbox-disabled label {
  cursor: default;
  color: var(--mat-checkbox-disabled-label-color, color-mix(in srgb, var(--mat-sys-on-surface) 38%, transparent));
}
@media (forced-colors: active) {
  .mat-mdc-checkbox.mat-mdc-checkbox-disabled label {
    color: GrayText;
  }
}
.mat-mdc-checkbox label:empty {
  display: none;
}
.mat-mdc-checkbox .mdc-checkbox__ripple {
  opacity: 0;
}

.mat-mdc-checkbox .mat-mdc-checkbox-ripple,
.mdc-checkbox__ripple {
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  position: absolute;
  border-radius: 50%;
  pointer-events: none;
}
.mat-mdc-checkbox .mat-mdc-checkbox-ripple:not(:empty),
.mdc-checkbox__ripple:not(:empty) {
  transform: translateZ(0);
}

.mat-mdc-checkbox-ripple .mat-ripple-element {
  opacity: 0.1;
}

.mat-mdc-checkbox-touch-target {
  position: absolute;
  top: 50%;
  left: 50%;
  height: var(--mat-checkbox-touch-target-size, 48px);
  width: var(--mat-checkbox-touch-target-size, 48px);
  transform: translate(-50%, -50%);
  display: var(--mat-checkbox-touch-target-display, block);
}

.mat-mdc-checkbox .mat-mdc-checkbox-ripple::before {
  border-radius: 50%;
}

.mdc-checkbox__native-control:focus-visible ~ .mat-focus-indicator::before {
  content: "";
}
`],encapsulation:2})}return n})(),Ra=(()=>{class n{static \u0275fac=function(t){return new(t||n)};static \u0275mod=bE({type:n});static \u0275inj=cu$1({imports:[lc,jo$1]})}return n})();var dc=["tooltip"],cc=20;var mc=new x("mat-tooltip-scroll-strategy",{providedIn:"root",factory:()=>{let n=T(ge);return ()=>gi$1(n,{scrollThrottle:cc})}}),hc=new x("mat-tooltip-default-options",{providedIn:"root",factory:()=>({showDelay:0,hideDelay:0,touchendHideDelay:1500})});var Rs="tooltip-panel",pc={passive:true},uc=8,_c=8,fc=24,gc=200,Fi=(()=>{class n{_elementRef=T(Dr$1);_ngZone=T(Se);_platform=T(D);_ariaDescriber=T(Kd$1);_focusMonitor=T(le);_dir=T(Er$2);_injector=T(ge);_viewContainerRef=T(Ai$1);_mediaMatcher=T(cn);_document=T(or$1);_renderer=T(MI);_animationsDisabled=B();_defaultOptions=T(hc,{optional:true});_overlayRef=null;_tooltipInstance=null;_overlayPanelClass;_portal;_position="below";_positionAtOrigin=false;_disabled=false;_tooltipClass;_viewInitialized=false;_pointerExitEventsInitialized=false;_tooltipComponent=Fs;_viewportMargin=8;_currentPosition;_cssClassPrefix="mat-mdc";_ariaDescriptionPending=false;_dirSubscribed=false;get position(){return this._position}set position(e){e!==this._position&&(this._position=e,this._overlayRef&&(this._updatePosition(this._overlayRef),this._tooltipInstance?.show(0),this._overlayRef.updatePosition()));}get positionAtOrigin(){return this._positionAtOrigin}set positionAtOrigin(e){this._positionAtOrigin=mm$1(e),this._detach(),this._overlayRef=null;}get disabled(){return this._disabled}set disabled(e){let t=mm$1(e);this._disabled!==t&&(this._disabled=t,t?this.hide(0):this._setupPointerEnterEventsIfNeeded(),this._syncAriaDescription(this.message));}get showDelay(){return this._showDelay}set showDelay(e){this._showDelay=Ht(e);}_showDelay;get hideDelay(){return this._hideDelay}set hideDelay(e){this._hideDelay=Ht(e),this._tooltipInstance&&(this._tooltipInstance._mouseLeaveHideDelay=this._hideDelay);}_hideDelay;touchGestures="auto";get message(){return this._message}set message(e){let t=this._message;this._message=e!=null?String(e).trim():"",!this._message&&this._isTooltipVisible()?this.hide(0):(this._setupPointerEnterEventsIfNeeded(),this._updateTooltipMessage()),this._syncAriaDescription(t);}_message="";get tooltipClass(){return this._tooltipClass}set tooltipClass(e){this._tooltipClass=e,this._tooltipInstance&&this._setTooltipClass(this._tooltipClass);}_eventCleanups=[];_touchstartTimeout=null;_destroyed=new ne;_isDestroyed=false;constructor(){let e=this._defaultOptions;e&&(this._showDelay=e.showDelay,this._hideDelay=e.hideDelay,e.position&&(this.position=e.position),e.positionAtOrigin&&(this.positionAtOrigin=e.positionAtOrigin),e.touchGestures&&(this.touchGestures=e.touchGestures),e.tooltipClass&&(this.tooltipClass=e.tooltipClass)),this._viewportMargin=uc;}ngAfterViewInit(){this._viewInitialized=true,this._setupPointerEnterEventsIfNeeded(),this._focusMonitor.monitor(this._elementRef).pipe(Bl$1(this._destroyed)).subscribe(e=>{e?e==="keyboard"&&this._ngZone.run(()=>this.show()):this._ngZone.run(()=>this.hide(0));});}ngOnDestroy(){let e=this._elementRef.nativeElement;this._touchstartTimeout&&clearTimeout(this._touchstartTimeout),this._overlayRef&&(this._overlayRef.dispose(),this._tooltipInstance=null),this._eventCleanups.forEach(t=>t()),this._eventCleanups.length=0,this._destroyed.next(),this._destroyed.complete(),this._isDestroyed=true,this._ariaDescriber.removeDescription(e,this.message,"tooltip"),this._focusMonitor.stopMonitoring(e);}show(e=this.showDelay,t){if(this.disabled||!this.message||this._isTooltipVisible()){this._tooltipInstance?._cancelPendingAnimations();return}let i=this._createOverlay(t);this._detach(),this._portal=this._portal||new bt(this._tooltipComponent,this._viewContainerRef);let a=this._tooltipInstance=i.attach(this._portal).instance;a._triggerElement=this._elementRef.nativeElement,a._mouseLeaveHideDelay=this._hideDelay,a.afterHidden().pipe(Bl$1(this._destroyed)).subscribe(()=>this._detach()),this._setTooltipClass(this._tooltipClass),this._updateTooltipMessage(),a.show(e);}hide(e=this.hideDelay){let t=this._tooltipInstance;t&&(t.isVisible()?t.hide(e):(t._cancelPendingAnimations(),this._detach()));}toggle(e){this._isTooltipVisible()?this.hide():this.show(void 0,e);}_isTooltipVisible(){return !!this._tooltipInstance&&this._tooltipInstance.isVisible()}_createOverlay(e){if(this._overlayRef){let o=this._overlayRef.getConfig().positionStrategy;if((!this.positionAtOrigin||!e)&&o._origin instanceof Dr$1)return this._overlayRef;this._detach();}let t=this._injector.get(Le).getAncestorScrollContainers(this._elementRef),i=`${this._cssClassPrefix}-${Rs}`,a=vi(this._injector,this.positionAtOrigin?e||this._elementRef:this._elementRef).withTransformOriginOn(`.${this._cssClassPrefix}-tooltip`).withFlexibleDimensions(false).withViewportMargin(this._viewportMargin).withScrollableContainers(t).withPopoverLocation("global");return a.positionChanges.pipe(Bl$1(this._destroyed)).subscribe(o=>{this._updateCurrentPositionClass(o.connectionPair),this._tooltipInstance&&o.scrollableViewProperties.isOverlayClipped&&this._tooltipInstance.isVisible()&&this._ngZone.run(()=>this.hide(0));}),this._overlayRef=Zt(this._injector,{direction:this._dir,positionStrategy:a,panelClass:this._overlayPanelClass?[...this._overlayPanelClass,i]:i,scrollStrategy:this._injector.get(mc)(),disableAnimations:this._animationsDisabled,eventPredicate:this._overlayEventPredicate}),this._updatePosition(this._overlayRef),this._overlayRef.detachments().pipe(Bl$1(this._destroyed)).subscribe(()=>this._detach()),this._overlayRef.outsidePointerEvents().pipe(Bl$1(this._destroyed)).subscribe(()=>this._tooltipInstance?._handleBodyInteraction()),this._overlayRef.keydownEvents().pipe(Bl$1(this._destroyed)).subscribe(o=>{o.preventDefault(),o.stopPropagation(),this._ngZone.run(()=>this.hide(0));}),this._defaultOptions?.disableTooltipInteractivity&&this._overlayRef.addPanelClass(`${this._cssClassPrefix}-tooltip-panel-non-interactive`),this._dirSubscribed||(this._dirSubscribed=true,this._dir.change.pipe(Bl$1(this._destroyed)).subscribe(()=>{this._overlayRef&&this._updatePosition(this._overlayRef);})),this._overlayRef}_detach(){this._overlayRef&&this._overlayRef.hasAttached()&&this._overlayRef.detach(),this._tooltipInstance=null;}_updatePosition(e){let t=e.getConfig().positionStrategy,i=this._getOrigin(),a=this._getOverlayPosition();t.withPositions([this._addOffset(q(q({},i.main),a.main)),this._addOffset(q(q({},i.fallback),a.fallback))]);}_addOffset(e){let t=_c,i=!this._dir||this._dir.value=="ltr";return e.originY==="top"?e.offsetY=-t:e.originY==="bottom"?e.offsetY=t:e.originX==="start"?e.offsetX=i?-t:t:e.originX==="end"&&(e.offsetX=i?t:-t),e}_getOrigin(){let e=!this._dir||this._dir.value=="ltr",t=this.position,i;t=="above"||t=="below"?i={originX:"center",originY:t=="above"?"top":"bottom"}:t=="before"||t=="left"&&e||t=="right"&&!e?i={originX:"start",originY:"center"}:(t=="after"||t=="right"&&e||t=="left"&&!e)&&(i={originX:"end",originY:"center"});let{x:a,y:o}=this._invertPosition(i.originX,i.originY);return {main:i,fallback:{originX:a,originY:o}}}_getOverlayPosition(){let e=!this._dir||this._dir.value=="ltr",t=this.position,i;t=="above"?i={overlayX:"center",overlayY:"bottom"}:t=="below"?i={overlayX:"center",overlayY:"top"}:t=="before"||t=="left"&&e||t=="right"&&!e?i={overlayX:"end",overlayY:"center"}:(t=="after"||t=="right"&&e||t=="left"&&!e)&&(i={overlayX:"start",overlayY:"center"});let{x:a,y:o}=this._invertPosition(i.overlayX,i.overlayY);return {main:i,fallback:{overlayX:a,overlayY:o}}}_updateTooltipMessage(){this._tooltipInstance&&(this._tooltipInstance.message=this.message,this._tooltipInstance._markForCheck(),Dv(()=>{this._tooltipInstance&&this._overlayRef.updatePosition();},{injector:this._injector}));}_setTooltipClass(e){this._tooltipInstance&&(this._tooltipInstance.tooltipClass=e instanceof Set?Array.from(e):e,this._tooltipInstance._markForCheck());}_invertPosition(e,t){return this.position==="above"||this.position==="below"?t==="top"?t="bottom":t==="bottom"&&(t="top"):e==="end"?e="start":e==="start"&&(e="end"),{x:e,y:t}}_updateCurrentPositionClass(e){let{overlayY:t,originX:i,originY:a}=e,o;if(t==="center"?this._dir&&this._dir.value==="rtl"?o=i==="end"?"left":"right":o=i==="start"?"left":"right":o=t==="bottom"&&a==="top"?"above":"below",o!==this._currentPosition){let c=this._overlayRef;if(c){let y=`${this._cssClassPrefix}-${Rs}-`;c.removePanelClass(y+this._currentPosition),c.addPanelClass(y+o);}this._currentPosition=o;}}_setupPointerEnterEventsIfNeeded(){this._disabled||!this.message||!this._viewInitialized||this._eventCleanups.length||(this._isTouchPlatform()?this.touchGestures!=="off"&&(this._disableNativeGesturesIfNecessary(),this._addListener("touchstart",e=>{let t=e.targetTouches?.[0],i=t?{x:t.clientX,y:t.clientY}:void 0;this._setupPointerExitEventsIfNeeded(),this._touchstartTimeout&&clearTimeout(this._touchstartTimeout);let a=500;this._touchstartTimeout=setTimeout(()=>{this._touchstartTimeout=null,this.show(void 0,i);},this._defaultOptions?.touchLongPressShowDelay??a);})):this._addListener("mouseenter",e=>{this._setupPointerExitEventsIfNeeded();let t;e.x!==void 0&&e.y!==void 0&&(t=e),this.show(void 0,t);}));}_setupPointerExitEventsIfNeeded(){if(!this._pointerExitEventsInitialized){if(this._pointerExitEventsInitialized=true,!this._isTouchPlatform())this._addListener("mouseleave",e=>{let t=e.relatedTarget;(!t||!this._overlayRef?.overlayElement.contains(t))&&this.hide();}),this._addListener("wheel",e=>{if(this._isTooltipVisible()){let t=this._document.elementFromPoint(e.clientX,e.clientY),i=this._elementRef.nativeElement;t!==i&&!i.contains(t)&&this.hide();}});else if(this.touchGestures!=="off"){this._disableNativeGesturesIfNecessary();let e=()=>{this._touchstartTimeout&&clearTimeout(this._touchstartTimeout),this.hide(this._defaultOptions?.touchendHideDelay);};this._addListener("touchend",e),this._addListener("touchcancel",e);}}}_addListener(e,t){this._eventCleanups.push(this._renderer.listen(this._elementRef.nativeElement,e,t,pc));}_isTouchPlatform(){let e=this._defaultOptions?.detectHoverCapability;return typeof e=="function"?!e():this._platform.IOS||this._platform.ANDROID?true:this._platform.isBrowser?!!e&&this._mediaMatcher.matchMedia("(any-hover: none)").matches:false}_disableNativeGesturesIfNecessary(){let e=this.touchGestures;if(e!=="off"){let t=this._elementRef.nativeElement,i=t.style;(e==="on"||t.nodeName!=="INPUT"&&t.nodeName!=="TEXTAREA")&&(i.userSelect=i.msUserSelect=i.webkitUserSelect=i.MozUserSelect="none"),(e==="on"||!t.draggable)&&(i.webkitUserDrag="none"),i.touchAction="none",i.webkitTapHighlightColor="transparent";}}_syncAriaDescription(e){this._ariaDescriptionPending||(this._ariaDescriptionPending=true,this._ariaDescriber.removeDescription(this._elementRef.nativeElement,e,"tooltip"),this._isDestroyed||Dv({write:()=>{this._ariaDescriptionPending=false,this.message&&!this.disabled&&this._ariaDescriber.describe(this._elementRef.nativeElement,this.message,"tooltip");}},{injector:this._injector}));}_overlayEventPredicate=e=>e.type==="keydown"?this._isTooltipVisible()&&e.keyCode===27&&!$(e):true;static \u0275fac=function(t){return new(t||n)};static \u0275dir=SE({type:n,selectors:[["","matTooltip",""]],hostAttrs:[1,"mat-mdc-tooltip-trigger"],hostVars:2,hostBindings:function(t,i){t&2&&fh$1("mat-mdc-tooltip-disabled",i.disabled);},inputs:{position:[0,"matTooltipPosition","position"],positionAtOrigin:[0,"matTooltipPositionAtOrigin","positionAtOrigin"],disabled:[0,"matTooltipDisabled","disabled"],showDelay:[0,"matTooltipShowDelay","showDelay"],hideDelay:[0,"matTooltipHideDelay","hideDelay"],touchGestures:[0,"matTooltipTouchGestures","touchGestures"],message:[0,"matTooltip","message"],tooltipClass:[0,"matTooltipClass","tooltipClass"]},exportAs:["matTooltip"]})}return n})(),Fs=(()=>{class n{_changeDetectorRef=T(L0);_elementRef=T(Dr$1);_isMultiline=false;message;tooltipClass;_showTimeoutId;_hideTimeoutId;_triggerElement;_mouseLeaveHideDelay;_animationsDisabled=B();_tooltip;_closeOnInteraction=false;_isVisible=false;_onHide=new ne;_showAnimation="mat-mdc-tooltip-show";_hideAnimation="mat-mdc-tooltip-hide";show(e){this._hideTimeoutId!=null&&clearTimeout(this._hideTimeoutId),this._showTimeoutId=setTimeout(()=>{this._toggleVisibility(true),this._showTimeoutId=void 0;},e);}hide(e){this._showTimeoutId!=null&&clearTimeout(this._showTimeoutId),this._hideTimeoutId=setTimeout(()=>{this._toggleVisibility(false),this._hideTimeoutId=void 0;},e);}afterHidden(){return this._onHide}isVisible(){return this._isVisible}ngOnDestroy(){this._cancelPendingAnimations(),this._onHide.complete(),this._triggerElement=null;}_handleBodyInteraction(){this._closeOnInteraction&&this.hide(0);}_markForCheck(){this._changeDetectorRef.markForCheck();}_handleMouseLeave({relatedTarget:e}){(!e||!this._triggerElement.contains(e))&&(this.isVisible()?this.hide(this._mouseLeaveHideDelay):this._finalizeAnimation(false));}_onShow(){this._isMultiline=this._isTooltipMultiline(),this._markForCheck();}_isTooltipMultiline(){let e=this._elementRef.nativeElement.getBoundingClientRect();return e.height>fc&&e.width>=gc}_handleAnimationEnd({animationName:e}){(e===this._showAnimation||e===this._hideAnimation)&&this._finalizeAnimation(e===this._showAnimation);}_cancelPendingAnimations(){this._showTimeoutId!=null&&clearTimeout(this._showTimeoutId),this._hideTimeoutId!=null&&clearTimeout(this._hideTimeoutId),this._showTimeoutId=this._hideTimeoutId=void 0;}_finalizeAnimation(e){e?this._closeOnInteraction=true:this.isVisible()||this._onHide.next();}_toggleVisibility(e){let t=this._tooltip.nativeElement,i=this._showAnimation,a=this._hideAnimation;if(t.classList.remove(e?a:i),t.classList.add(e?i:a),this._isVisible!==e&&(this._isVisible=e,this._changeDetectorRef.markForCheck()),e&&!this._animationsDisabled&&typeof getComputedStyle=="function"){let o=getComputedStyle(t);(o.getPropertyValue("animation-duration")==="0s"||o.getPropertyValue("animation-name")==="none")&&(this._animationsDisabled=true);}e&&this._onShow(),this._animationsDisabled&&(t.classList.add("_mat-animation-noopable"),this._finalizeAnimation(e));}static \u0275fac=function(t){return new(t||n)};static \u0275cmp=TE({type:n,selectors:[["mat-tooltip-component"]],viewQuery:function(t,i){if(t&1&&sh$1(dc,7),t&2){let a;vD(a=ID())&&(i._tooltip=a.first);}},hostAttrs:["aria-hidden","true"],hostBindings:function(t,i){t&1&&nh$1("mouseleave",function(o){return i._handleMouseLeave(o)});},decls:4,vars:5,consts:[["tooltip",""],[1,"mdc-tooltip","mat-mdc-tooltip",3,"animationend"],[1,"mat-mdc-tooltip-surface","mdc-tooltip__surface"]],template:function(t,i){t&1&&(Vc$1(0,"div",1,0),rh$1("animationend",function(o){return i._handleAnimationEnd(o)}),Vc$1(2,"div",2),UD(3),Hc$1()()),t&2&&(RD(i.tooltipClass),fh$1("mdc-tooltip--multiline",i._isMultiline),Wv(3),Ih$1(i.message));},styles:[`.mat-mdc-tooltip {
  position: relative;
  transform: scale(0);
  display: inline-flex;
}
.mat-mdc-tooltip::before {
  content: "";
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  z-index: -1;
  position: absolute;
}
.mat-mdc-tooltip-panel-below .mat-mdc-tooltip::before {
  top: -8px;
}
.mat-mdc-tooltip-panel-above .mat-mdc-tooltip::before {
  bottom: -8px;
}
.mat-mdc-tooltip-panel-right .mat-mdc-tooltip::before {
  left: -8px;
}
.mat-mdc-tooltip-panel-left .mat-mdc-tooltip::before {
  right: -8px;
}
.mat-mdc-tooltip._mat-animation-noopable {
  animation: none;
  transform: scale(1);
}

.mat-mdc-tooltip-surface {
  word-break: normal;
  overflow-wrap: anywhere;
  padding: 4px 8px;
  min-width: 40px;
  max-width: 200px;
  min-height: 24px;
  max-height: 40vh;
  box-sizing: border-box;
  overflow: hidden;
  text-align: center;
  will-change: transform, opacity;
  background-color: var(--mat-tooltip-container-color, var(--mat-sys-inverse-surface));
  color: var(--mat-tooltip-supporting-text-color, var(--mat-sys-inverse-on-surface));
  border-radius: var(--mat-tooltip-container-shape, var(--mat-sys-corner-extra-small));
  font-family: var(--mat-tooltip-supporting-text-font, var(--mat-sys-body-small-font));
  font-size: var(--mat-tooltip-supporting-text-size, var(--mat-sys-body-small-size));
  font-weight: var(--mat-tooltip-supporting-text-weight, var(--mat-sys-body-small-weight));
  line-height: var(--mat-tooltip-supporting-text-line-height, var(--mat-sys-body-small-line-height));
  letter-spacing: var(--mat-tooltip-supporting-text-tracking, var(--mat-sys-body-small-tracking));
}
.mat-mdc-tooltip-surface::before {
  position: absolute;
  box-sizing: border-box;
  width: 100%;
  height: 100%;
  top: 0;
  left: 0;
  border: 1px solid transparent;
  border-radius: inherit;
  content: "";
  pointer-events: none;
}
.mdc-tooltip--multiline .mat-mdc-tooltip-surface {
  text-align: left;
}
[dir=rtl] .mdc-tooltip--multiline .mat-mdc-tooltip-surface {
  text-align: right;
}

.mat-mdc-tooltip-panel {
  line-height: normal;
}
.mat-mdc-tooltip-panel.mat-mdc-tooltip-panel-non-interactive {
  pointer-events: none;
}

@keyframes mat-mdc-tooltip-show {
  0% {
    opacity: 0;
    transform: scale(0.8);
  }
  100% {
    opacity: 1;
    transform: scale(1);
  }
}
@keyframes mat-mdc-tooltip-hide {
  0% {
    opacity: 1;
    transform: scale(1);
  }
  100% {
    opacity: 0;
    transform: scale(0.8);
  }
}
.mat-mdc-tooltip-show {
  animation: mat-mdc-tooltip-show 150ms cubic-bezier(0, 0, 0.2, 1) forwards;
}

.mat-mdc-tooltip-hide {
  animation: mat-mdc-tooltip-hide 75ms cubic-bezier(0.4, 0, 1, 1) forwards;
}
`],encapsulation:2})}return n})();var K_=(()=>{class n{get vertical(){return this._vertical}set vertical(e){this._vertical=mm$1(e);}_vertical=false;get inset(){return this._inset}set inset(e){this._inset=mm$1(e);}_inset=false;static \u0275fac=function(t){return new(t||n)};static \u0275cmp=TE({type:n,selectors:[["mat-divider"]],hostAttrs:["role","separator",1,"mat-divider"],hostVars:7,hostBindings:function(t,i){t&2&&(Qp("aria-orientation",i.vertical?"vertical":"horizontal"),fh$1("mat-divider-vertical",i.vertical)("mat-divider-horizontal",!i.vertical)("mat-divider-inset",i.inset));},inputs:{vertical:"vertical",inset:"inset"},decls:0,vars:0,template:function(t,i){},styles:[`.mat-divider {
  display: block;
  margin: 0;
  border-top-style: solid;
  border-top-color: var(--mat-divider-color, var(--mat-sys-outline-variant));
  border-top-width: var(--mat-divider-width, 1px);
}
.mat-divider.mat-divider-vertical {
  border-top: 0;
  border-right-style: solid;
  border-right-color: var(--mat-divider-color, var(--mat-sys-outline-variant));
  border-right-width: var(--mat-divider-width, 1px);
}
.mat-divider.mat-divider-inset {
  margin-left: 80px;
}
[dir=rtl] .mat-divider.mat-divider-inset {
  margin-left: auto;
  margin-right: 80px;
}
`],encapsulation:2})}return n})(),Oi=(()=>{class n{static \u0275fac=function(t){return new(t||n)};static \u0275mod=bE({type:n});static \u0275inj=cu$1({imports:[jo$1]})}return n})();var qt=(()=>{class n{_listeners=[];notify(e,t){for(let i of this._listeners)i(e,t);}listen(e){return this._listeners.push(e),()=>{this._listeners=this._listeners.filter(t=>e!==t);}}ngOnDestroy(){this._listeners=[];}static \u0275fac=function(t){return new(t||n)};static \u0275prov=Er$1({token:n,factory:n.\u0275fac})}return n})();var Fa=new x("CdkAccordion"),Os=(()=>{class n{_stateChanges=new ne;_openCloseAllActions=new ne;id=T(Y).getId("cdk-accordion-");multi=false;openAll(){this.multi&&this._openCloseAllActions.next(true);}closeAll(){this._openCloseAllActions.next(false);}ngOnChanges(e){this._stateChanges.next(e);}ngOnDestroy(){this._stateChanges.complete(),this._openCloseAllActions.complete();}static \u0275fac=function(t){return new(t||n)};static \u0275dir=SE({type:n,selectors:[["cdk-accordion"],["","cdkAccordion",""]],inputs:{multi:[2,"multi","multi",j0]},exportAs:["cdkAccordion"],features:[JD([{provide:Fa,useExisting:n}]),zm$1]})}return n})(),Ps=(()=>{class n{accordion=T(Fa,{optional:true,skipSelf:true});_changeDetectorRef=T(L0);_expansionDispatcher=T(qt);_openCloseAllSubscription=j.EMPTY;closed=new $e;opened=new $e;destroyed=new $e;expandedChange=new $e;id=T(Y).getId("cdk-accordion-child-");get expanded(){return this._expanded}set expanded(e){if(this._expanded!==e){if(this._expanded=e,this.expandedChange.emit(e),e){this.opened.emit();let t=this.accordion?this.accordion.id:this.id;this._expansionDispatcher.notify(this.id,t);}else this.closed.emit();this._changeDetectorRef.markForCheck();}}_expanded=false;get disabled(){return this._disabled()}set disabled(e){this._disabled.set(e);}_disabled=Vo$1(false);_removeUniqueSelectionListener=()=>{};ngOnInit(){this._removeUniqueSelectionListener=this._expansionDispatcher.listen((e,t)=>{this.accordion&&!this.accordion.multi&&this.accordion.id===t&&this.id!==e&&(this.expanded=false);}),this.accordion&&(this._openCloseAllSubscription=this._subscribeToOpenCloseAllActions());}ngOnDestroy(){this.opened.complete(),this.closed.complete(),this.destroyed.emit(),this.destroyed.complete(),this._removeUniqueSelectionListener(),this._openCloseAllSubscription.unsubscribe();}toggle(){this.disabled||(this.expanded=!this.expanded);}close(){this.disabled||(this.expanded=false);}open(){this.disabled||(this.expanded=true);}_subscribeToOpenCloseAllActions(){return this.accordion._openCloseAllActions.subscribe(e=>{this.disabled||(this.expanded=e);})}static \u0275fac=function(t){return new(t||n)};static \u0275dir=SE({type:n,selectors:[["cdk-accordion-item"],["","cdkAccordionItem",""]],inputs:{expanded:[2,"expanded","expanded",j0],disabled:[2,"disabled","disabled",j0]},outputs:{closed:"closed",opened:"opened",destroyed:"destroyed",expandedChange:"expandedChange"},exportAs:["cdkAccordionItem"],features:[JD([{provide:Fa,useValue:void 0}])]})}return n})(),Vs=(()=>{class n{static \u0275fac=function(t){return new(t||n)};static \u0275mod=bE({type:n});static \u0275inj=cu$1({})}return n})();var Qt=class{_multiple;_emitChanges;compareWith;_selection=new Set;_deselectedToEmit=[];_selectedToEmit=[];_selected=null;get selected(){return this._selected||(this._selected=Array.from(this._selection.values())),this._selected}changed=new ne;bulk={select:r=>this._select(r),deselect:r=>this._deselect(r),setSelection:r=>this._setSelection(r)};constructor(r=false,e,t=true,i){this._multiple=r,this._emitChanges=t,this.compareWith=i,e&&e.length&&(r?e.forEach(a=>this._markSelected(a)):this._markSelected(e[0]),this._selectedToEmit.length=0);}select(...r){return this._select(r)}deselect(...r){return this._deselect(r)}setSelection(...r){return this._setSelection(r)}toggle(r){return this.isSelected(r)?this.deselect(r):this.select(r)}clear(r=true){this._unmarkAll();let e=this._hasQueuedChanges();return r&&this._emitChangeEvent(),e}isSelected(r){return this._selection.has(this._getConcreteValue(r))}isEmpty(){return this._selection.size===0}hasValue(){return !this.isEmpty()}sort(r){this._multiple&&this.selected&&this._selected.sort(r);}isMultipleSelection(){return this._multiple}_select(r){this._verifyValueAssignment(r),r.forEach(t=>this._markSelected(t));let e=this._hasQueuedChanges();return this._emitChangeEvent(),e}_deselect(r){this._verifyValueAssignment(r),r.forEach(t=>this._unmarkSelected(t));let e=this._hasQueuedChanges();return this._emitChangeEvent(),e}_setSelection(r){this._verifyValueAssignment(r);let e=this.selected,t=new Set(r.map(a=>this._getConcreteValue(a)));r.forEach(a=>this._markSelected(a)),e.filter(a=>!t.has(this._getConcreteValue(a,t))).forEach(a=>this._unmarkSelected(a));let i=this._hasQueuedChanges();return this._emitChangeEvent(),i}_emitChangeEvent(){this._selected=null,(this._selectedToEmit.length||this._deselectedToEmit.length)&&(this.changed.next({source:this,added:this._selectedToEmit,removed:this._deselectedToEmit}),this._deselectedToEmit=[],this._selectedToEmit=[]);}_markSelected(r){r=this._getConcreteValue(r),this.isSelected(r)||(this._multiple||this._unmarkAll(),this.isSelected(r)||this._selection.add(r),this._emitChanges&&this._selectedToEmit.push(r));}_unmarkSelected(r){r=this._getConcreteValue(r),this.isSelected(r)&&(this._selection.delete(r),this._emitChanges&&this._deselectedToEmit.push(r));}_unmarkAll(){this.isEmpty()||this._selection.forEach(r=>this._unmarkSelected(r));}_verifyValueAssignment(r){r.length>1&&this._multiple;}_hasQueuedChanges(){return !!(this._deselectedToEmit.length||this._selectedToEmit.length)}_getConcreteValue(r,e){if(this.compareWith){e=e??this._selection;for(let t of e)if(this.compareWith(r,t))return t;return r}else return r}};var Tn=class{applyChanges(r,e,t,i,a){r.forEachOperation((o,c,y)=>{let F,H;if(o.previousIndex==null){let De=t(o,c,y);F=e.createEmbeddedView(De.templateRef,De.context,De.index),H=Ne.INSERTED;}else y==null?(e.remove(c),H=Ne.REMOVED):(F=e.get(c),e.move(F,y),H=Ne.MOVED);a&&a({context:F?.context,operation:H,record:o});});}detach(){}};var bc=["body"],vc=["bodyWrapper"],yc=[[["mat-expansion-panel-header"]],"*",[["mat-action-row"]]],xc=["mat-expansion-panel-header","*","mat-action-row"];function wc(n,r){}var kc=[[["mat-panel-title"]],[["mat-panel-description"]],"*"],Cc=["mat-panel-title","mat-panel-description","*"];function Dc(n,r){n&1&&(Vc$1(0,"span",1),Gu(),Vc$1(1,"svg",2),Kp(2,"path",3),Hc$1()());}var Oa=new x("MAT_ACCORDION"),Ls=new x("MAT_EXPANSION_PANEL"),Mc=(()=>{class n{_template=T(hr$1);_expansionPanel=T(Ls,{optional:true});static \u0275fac=function(t){return new(t||n)};static \u0275dir=SE({type:n,selectors:[["ng-template","matExpansionPanelContent",""]]})}return n})(),Ns=new x("MAT_EXPANSION_PANEL_DEFAULT_OPTIONS"),Sc=(()=>{class n extends Ps{_viewContainerRef=T(Ai$1);_animationsDisabled=B();_document=T(or$1);_ngZone=T(Se);_elementRef=T(Dr$1);_renderer=T(MI);_cleanupTransitionEnd;get hideToggle(){return this._hideToggle||this.accordion&&this.accordion.hideToggle}set hideToggle(e){this._hideToggle=e;}_hideToggle=false;get togglePosition(){return this._togglePosition||this.accordion&&this.accordion.togglePosition}set togglePosition(e){this._togglePosition=e;}_togglePosition;afterExpand=new $e;afterCollapse=new $e;_inputChanges=new ne;accordion=T(Oa,{optional:true,skipSelf:true});_lazyContent;_body;_bodyWrapper;_portal;_headerId=T(Y).getId("mat-expansion-panel-header-");constructor(){super();let e=T(Ns,{optional:true});this._expansionDispatcher=T(qt),e&&(this.hideToggle=e.hideToggle);}_hasSpacing(){return this.accordion?this.expanded&&this.accordion.displayMode==="default":false}_getExpandedState(){return this.expanded?"expanded":"collapsed"}toggle(){this.expanded=!this.expanded;}close(){this.expanded=false;}open(){this.expanded=true;}ngAfterContentInit(){this._lazyContent&&this._lazyContent._expansionPanel===this&&this.opened.pipe(Hl$1(null),en(()=>this.expanded&&!this._portal),tn(1)).subscribe(()=>{this._portal=new at$1(this._lazyContent._template,this._viewContainerRef);}),this._setupAnimationEvents();}ngOnChanges(e){this._inputChanges.next(e);}ngOnDestroy(){super.ngOnDestroy(),this._cleanupTransitionEnd?.(),this._inputChanges.complete();}_containsFocus(){if(this._body){let e=this._document.activeElement,t=this._body.nativeElement;return e===t||t.contains(e)}return  false}_transitionEndListener=({target:e,propertyName:t})=>{e===this._bodyWrapper?.nativeElement&&t==="grid-template-rows"&&this._ngZone.run(()=>{this.expanded?this.afterExpand.emit():this.afterCollapse.emit();});};_setupAnimationEvents(){this._ngZone.runOutsideAngular(()=>{this._animationsDisabled?(this.opened.subscribe(()=>this._ngZone.run(()=>this.afterExpand.emit())),this.closed.subscribe(()=>this._ngZone.run(()=>this.afterCollapse.emit()))):setTimeout(()=>{let e=this._elementRef.nativeElement;this._cleanupTransitionEnd=this._renderer.listen(e,"transitionend",this._transitionEndListener),e.classList.add("mat-expansion-panel-animations-enabled");},200);});}static \u0275fac=function(t){return new(t||n)};static \u0275cmp=TE({type:n,selectors:[["mat-expansion-panel"]],contentQueries:function(t,i,a){if(t&1&&ih$1(a,Mc,5),t&2){let o;vD(o=ID())&&(i._lazyContent=o.first);}},viewQuery:function(t,i){if(t&1&&sh$1(bc,5)(vc,5),t&2){let a;vD(a=ID())&&(i._body=a.first),vD(a=ID())&&(i._bodyWrapper=a.first);}},hostAttrs:[1,"mat-expansion-panel"],hostVars:4,hostBindings:function(t,i){t&2&&fh$1("mat-expanded",i.expanded)("mat-expansion-panel-spacing",i._hasSpacing());},inputs:{hideToggle:[2,"hideToggle","hideToggle",j0],togglePosition:"togglePosition"},outputs:{afterExpand:"afterExpand",afterCollapse:"afterCollapse"},exportAs:["matExpansionPanel"],features:[JD([{provide:Oa,useValue:void 0},{provide:Ls,useExisting:n}]),Hp$1,zm$1],ngContentSelectors:xc,decls:9,vars:4,consts:[["bodyWrapper",""],["body",""],[1,"mat-expansion-panel-content-wrapper"],["role","region",1,"mat-expansion-panel-content",3,"id"],[1,"mat-expansion-panel-body"],[3,"cdkPortalOutlet"]],template:function(t,i){t&1&&(gD(yc),mD(0),yi(1,"div",2,0)(3,"div",3,1)(5,"div",4),mD(6,1),$p(7,wc,0,0,"ng-template",5),jc$1(),mD(8,2),jc$1()()),t&2&&(Wv(),Qp("inert",i.expanded?null:""),Wv(2),Zp("id",i.id),Qp("aria-labelledby",i._headerId),Wv(4),Zp("cdkPortalOutlet",i._portal));},dependencies:[st],styles:[`.mat-expansion-panel {
  box-sizing: content-box;
  display: block;
  margin: 0;
  overflow: hidden;
}
.mat-expansion-panel.mat-expansion-panel-animations-enabled {
  transition: margin 225ms cubic-bezier(0.4, 0, 0.2, 1), box-shadow 280ms cubic-bezier(0.4, 0, 0.2, 1);
}
.mat-expansion-panel {
  position: relative;
  background: var(--mat-expansion-container-background-color, var(--mat-sys-surface));
  color: var(--mat-expansion-container-text-color, var(--mat-sys-on-surface));
  border-radius: var(--mat-expansion-container-shape, 12px);
}
.mat-expansion-panel:not([class*=mat-elevation-z]) {
  box-shadow: var(--mat-expansion-container-elevation-shadow, 0px 3px 1px -2px rgba(0, 0, 0, 0.2), 0px 2px 2px 0px rgba(0, 0, 0, 0.14), 0px 1px 5px 0px rgba(0, 0, 0, 0.12));
}
.mat-accordion .mat-expansion-panel:not(.mat-expanded), .mat-accordion .mat-expansion-panel:not(.mat-expansion-panel-spacing) {
  border-radius: 0;
}
.mat-accordion .mat-expansion-panel:first-of-type {
  border-top-right-radius: var(--mat-expansion-container-shape, 12px);
  border-top-left-radius: var(--mat-expansion-container-shape, 12px);
}
.mat-accordion .mat-expansion-panel:last-of-type {
  border-bottom-right-radius: var(--mat-expansion-container-shape, 12px);
  border-bottom-left-radius: var(--mat-expansion-container-shape, 12px);
}
@media (forced-colors: active) {
  .mat-expansion-panel {
    outline: solid 1px;
  }
}

.mat-expansion-panel-content-wrapper {
  display: grid;
  grid-template-rows: 0fr;
  grid-template-columns: 100%;
}
.mat-expansion-panel-animations-enabled .mat-expansion-panel-content-wrapper {
  transition: grid-template-rows 225ms cubic-bezier(0.4, 0, 0.2, 1);
}
.mat-expansion-panel.mat-expanded > .mat-expansion-panel-content-wrapper {
  grid-template-rows: 1fr;
}
@supports not (grid-template-rows: 0fr) {
  .mat-expansion-panel-content-wrapper {
    height: 0;
  }
  .mat-expansion-panel.mat-expanded > .mat-expansion-panel-content-wrapper {
    height: auto;
  }
}
@media print {
  .mat-expansion-panel-content-wrapper {
    height: 0;
  }
  .mat-expansion-panel.mat-expanded > .mat-expansion-panel-content-wrapper {
    height: auto;
  }
}

.mat-expansion-panel-content {
  display: flex;
  flex-direction: column;
  overflow: visible;
  min-height: 0;
  visibility: hidden;
}
.mat-expansion-panel-animations-enabled .mat-expansion-panel-content {
  transition: visibility 190ms linear;
}
.mat-expansion-panel.mat-expanded > .mat-expansion-panel-content-wrapper > .mat-expansion-panel-content {
  visibility: visible;
}
.mat-expansion-panel-content {
  font-family: var(--mat-expansion-container-text-font, var(--mat-sys-body-large-font));
  font-size: var(--mat-expansion-container-text-size, var(--mat-sys-body-large-size));
  font-weight: var(--mat-expansion-container-text-weight, var(--mat-sys-body-large-weight));
  line-height: var(--mat-expansion-container-text-line-height, var(--mat-sys-body-large-line-height));
  letter-spacing: var(--mat-expansion-container-text-tracking, var(--mat-sys-body-large-tracking));
}

.mat-expansion-panel-body {
  padding: 0 24px 16px;
}

.mat-expansion-panel-spacing {
  margin: 16px 0;
}
.mat-accordion > .mat-expansion-panel-spacing:first-child, .mat-accordion > *:first-child:not(.mat-expansion-panel) .mat-expansion-panel-spacing {
  margin-top: 0;
}
.mat-accordion > .mat-expansion-panel-spacing:last-child, .mat-accordion > *:last-child:not(.mat-expansion-panel) .mat-expansion-panel-spacing {
  margin-bottom: 0;
}

.mat-action-row {
  border-top-style: solid;
  border-top-width: 1px;
  display: flex;
  flex-direction: row;
  justify-content: flex-end;
  padding: 16px 8px 16px 24px;
  border-top-color: var(--mat-expansion-actions-divider-color, var(--mat-sys-outline));
}
.mat-action-row .mat-button-base,
.mat-action-row .mat-mdc-button-base {
  margin-left: 8px;
}
[dir=rtl] .mat-action-row .mat-button-base,
[dir=rtl] .mat-action-row .mat-mdc-button-base {
  margin-left: 0;
  margin-right: 8px;
}
`],encapsulation:2})}return n})();var Ic=(()=>{class n{panel=T(Sc,{host:true});_element=T(Dr$1);_focusMonitor=T(le);_changeDetectorRef=T(L0);_parentChangeSubscription=j.EMPTY;constructor(){T(W$1).load(bn$1);let e=this.panel,t=T(Ns,{optional:true}),i=T(new Ah$1("tabindex"),{optional:true}),a=e.accordion?e.accordion._stateChanges.pipe(en(o=>!!(o.hideToggle||o.togglePosition))):Dt;this.tabIndex=parseInt(i||"")||0,this._parentChangeSubscription=kg(e.opened,e.closed,a,e._inputChanges.pipe(en(o=>!!(o.hideToggle||o.disabled||o.togglePosition)))).subscribe(()=>this._changeDetectorRef.markForCheck()),e.closed.pipe(en(()=>e._containsFocus())).subscribe(()=>this._focusMonitor.focusVia(this._element,"program")),t&&(this.expandedHeight=t.expandedHeight,this.collapsedHeight=t.collapsedHeight);}expandedHeight;collapsedHeight;tabIndex=0;get disabled(){return this.panel.disabled}_toggle(){this.disabled||this.panel.toggle();}_isExpanded(){return this.panel.expanded}_getExpandedState(){return this.panel._getExpandedState()}_getPanelId(){return this.panel.id}_getTogglePosition(){return this.panel.togglePosition}_showToggle(){return !this.panel.hideToggle&&!this.panel.disabled}_getHeaderHeight(){let e=this._isExpanded();return e&&this.expandedHeight?this.expandedHeight:!e&&this.collapsedHeight?this.collapsedHeight:null}_keydown(e){switch(e.keyCode){case 32:case 13:$(e)||(e.preventDefault(),this._toggle());break;default:this.panel.accordion&&this.panel.accordion._handleHeaderKeydown(e);return}}focus(e,t){e?this._focusMonitor.focusVia(this._element,e,t):this._element.nativeElement.focus(t);}ngAfterViewInit(){this._focusMonitor.monitor(this._element).subscribe(e=>{e&&this.panel.accordion&&this.panel.accordion._handleHeaderFocus(this);});}ngOnDestroy(){this._parentChangeSubscription.unsubscribe(),this._focusMonitor.stopMonitoring(this._element);}static \u0275fac=function(t){return new(t||n)};static \u0275cmp=TE({type:n,selectors:[["mat-expansion-panel-header"]],hostAttrs:["role","button",1,"mat-expansion-panel-header","mat-focus-indicator"],hostVars:13,hostBindings:function(t,i){t&1&&nh$1("click",function(){return i._toggle()})("keydown",function(o){return i._keydown(o)}),t&2&&(Qp("id",i.panel._headerId)("tabindex",i.disabled?-1:i.tabIndex)("aria-controls",i._getPanelId())("aria-expanded",i._isExpanded())("aria-disabled",i.panel.disabled),dh$1("height",i._getHeaderHeight()),fh$1("mat-expanded",i._isExpanded())("mat-expansion-toggle-indicator-after",i._getTogglePosition()==="after")("mat-expansion-toggle-indicator-before",i._getTogglePosition()==="before"));},inputs:{expandedHeight:"expandedHeight",collapsedHeight:"collapsedHeight",tabIndex:[2,"tabIndex","tabIndex",e=>e==null?0:V0(e)]},ngContentSelectors:Cc,decls:5,vars:3,consts:[[1,"mat-content"],[1,"mat-expansion-indicator"],["xmlns","http://www.w3.org/2000/svg","viewBox","0 -960 960 960","aria-hidden","true","focusable","false"],["d","M480-345 240-585l56-56 184 184 184-184 56 56-240 240Z"]],template:function(t,i){t&1&&(gD(kc),Vc$1(0,"span",0),mD(1),mD(2,1),mD(3,2),Hc$1(),QE(4,Dc,3,0,"span",1)),t&2&&(fh$1("mat-content-hide-toggle",!i._showToggle()),Wv(4),ZE(i._showToggle()?4:-1));},styles:[`.mat-expansion-panel-header {
  display: flex;
  flex-direction: row;
  align-items: center;
  padding: 0 24px;
  border-radius: inherit;
}
.mat-expansion-panel-animations-enabled .mat-expansion-panel-header {
  transition: height 225ms cubic-bezier(0.4, 0, 0.2, 1);
}
.mat-expansion-panel-header::before {
  border-radius: inherit;
}
.mat-expansion-panel-header {
  height: var(--mat-expansion-header-collapsed-state-height, 48px);
  font-family: var(--mat-expansion-header-text-font, var(--mat-sys-title-medium-font));
  font-size: var(--mat-expansion-header-text-size, var(--mat-sys-title-medium-size));
  font-weight: var(--mat-expansion-header-text-weight, var(--mat-sys-title-medium-weight));
  line-height: var(--mat-expansion-header-text-line-height, var(--mat-sys-title-medium-line-height));
  letter-spacing: var(--mat-expansion-header-text-tracking, var(--mat-sys-title-medium-tracking));
}
.mat-expansion-panel-header.mat-expanded {
  height: var(--mat-expansion-header-expanded-state-height, 64px);
}
.mat-expansion-panel-header[aria-disabled=true] {
  color: var(--mat-expansion-header-disabled-state-text-color, color-mix(in srgb, var(--mat-sys-on-surface) 38%, transparent));
}
.mat-expansion-panel-header:not([aria-disabled=true]) {
  cursor: pointer;
}
.mat-expansion-panel:not(.mat-expanded) .mat-expansion-panel-header:not([aria-disabled=true]):hover {
  background: var(--mat-expansion-header-hover-state-layer-color, color-mix(in srgb, var(--mat-sys-on-surface) calc(var(--mat-sys-hover-state-layer-opacity) * 100%), transparent));
}
@media (hover: none) {
  .mat-expansion-panel:not(.mat-expanded) .mat-expansion-panel-header:not([aria-disabled=true]):hover {
    background: var(--mat-expansion-container-background-color, var(--mat-sys-surface));
  }
}
.mat-expansion-panel .mat-expansion-panel-header:not([aria-disabled=true]).cdk-keyboard-focused, .mat-expansion-panel .mat-expansion-panel-header:not([aria-disabled=true]).cdk-program-focused {
  background: var(--mat-expansion-header-focus-state-layer-color, color-mix(in srgb, var(--mat-sys-on-surface) calc(var(--mat-sys-focus-state-layer-opacity) * 100%), transparent));
}
.mat-expansion-panel-header._mat-animation-noopable {
  transition: none;
}
.mat-expansion-panel-header:focus, .mat-expansion-panel-header:hover {
  outline: none;
}
.mat-expansion-panel-header.mat-expanded:focus, .mat-expansion-panel-header.mat-expanded:hover {
  background: inherit;
}
.mat-expansion-panel-header.mat-expansion-toggle-indicator-before {
  flex-direction: row-reverse;
}
.mat-expansion-panel-header.mat-expansion-toggle-indicator-before .mat-expansion-indicator {
  margin: 0 16px 0 0;
}
[dir=rtl] .mat-expansion-panel-header.mat-expansion-toggle-indicator-before .mat-expansion-indicator {
  margin: 0 0 0 16px;
}

.mat-content {
  display: flex;
  flex: 1;
  flex-direction: row;
  overflow: hidden;
}
.mat-content.mat-content-hide-toggle {
  margin-right: 8px;
}
[dir=rtl] .mat-content.mat-content-hide-toggle {
  margin-right: 0;
  margin-left: 8px;
}
.mat-expansion-toggle-indicator-before .mat-content.mat-content-hide-toggle {
  margin-left: 24px;
  margin-right: 0;
}
[dir=rtl] .mat-expansion-toggle-indicator-before .mat-content.mat-content-hide-toggle {
  margin-right: 24px;
  margin-left: 0;
}

.mat-expansion-panel-header-title {
  color: var(--mat-expansion-header-text-color, var(--mat-sys-on-surface));
}

.mat-expansion-panel-header-title,
.mat-expansion-panel-header-description {
  display: flex;
  flex-grow: 1;
  flex-basis: 0;
  margin-right: 16px;
  align-items: center;
}
[dir=rtl] .mat-expansion-panel-header-title,
[dir=rtl] .mat-expansion-panel-header-description {
  margin-right: 0;
  margin-left: 16px;
}
.mat-expansion-panel-header[aria-disabled=true] .mat-expansion-panel-header-title,
.mat-expansion-panel-header[aria-disabled=true] .mat-expansion-panel-header-description {
  color: inherit;
}

.mat-expansion-panel-header-description {
  flex-grow: 2;
  color: var(--mat-expansion-header-description-color, var(--mat-sys-on-surface-variant));
}

.mat-expansion-panel-animations-enabled .mat-expansion-indicator {
  transition: transform 225ms cubic-bezier(0.4, 0, 0.2, 1);
}
.mat-expansion-panel-header.mat-expanded .mat-expansion-indicator {
  transform: rotate(180deg);
}
.mat-expansion-indicator::after {
  border-style: solid;
  border-width: 0 2px 2px 0;
  content: "";
  padding: 3px;
  transform: rotate(45deg);
  vertical-align: middle;
  color: var(--mat-expansion-header-indicator-color, var(--mat-sys-on-surface-variant));
  display: var(--mat-expansion-legacy-header-indicator-display, none);
}
.mat-expansion-indicator svg {
  width: 24px;
  height: 24px;
  margin: 0 -8px;
  vertical-align: middle;
  fill: var(--mat-expansion-header-indicator-color, var(--mat-sys-on-surface-variant));
  display: var(--mat-expansion-header-indicator-display, inline-block);
}

@media (forced-colors: active) {
  .mat-expansion-panel-content {
    border-top: 1px solid;
    border-top-left-radius: 0;
    border-top-right-radius: 0;
  }
}
`],encapsulation:2})}return n})(),Lf=(()=>{class n{static \u0275fac=function(t){return new(t||n)};static \u0275dir=SE({type:n,selectors:[["mat-panel-description"]],hostAttrs:[1,"mat-expansion-panel-header-description"]})}return n})(),Nf=(()=>{class n{static \u0275fac=function(t){return new(t||n)};static \u0275dir=SE({type:n,selectors:[["mat-panel-title"]],hostAttrs:[1,"mat-expansion-panel-header-title"]})}return n})(),Bf=(()=>{class n extends Os{_keyManager;_ownHeaders=new ri;_headers;hideToggle=false;displayMode="default";togglePosition="after";ngAfterContentInit(){this._headers.changes.pipe(Hl$1(this._headers)).subscribe(e=>{this._ownHeaders.reset(e.filter(t=>t.panel.accordion===this)),this._ownHeaders.notifyOnChanges();}),this._keyManager=new ni(this._ownHeaders).withWrap().withHomeAndEnd();}_handleHeaderKeydown(e){this._keyManager.onKeydown(e);}_handleHeaderFocus(e){this._keyManager.updateActiveItem(e);}ngOnDestroy(){super.ngOnDestroy(),this._keyManager?.destroy(),this._ownHeaders.destroy();}static \u0275fac=(()=>{let e;return function(i){return (e||(e=fy(n)))(i||n)}})();static \u0275dir=SE({type:n,selectors:[["mat-accordion"]],contentQueries:function(t,i,a){if(t&1&&ih$1(a,Ic,5),t&2){let o;vD(o=ID())&&(i._headers=o);}},hostAttrs:[1,"mat-accordion"],hostVars:2,hostBindings:function(t,i){t&2&&fh$1("mat-accordion-multi",i.multi);},inputs:{hideToggle:[2,"hideToggle","hideToggle",j0],displayMode:"displayMode",togglePosition:"togglePosition"},exportAs:["matAccordion"],features:[JD([{provide:Oa,useExisting:n}]),Hp$1]})}return n})(),Pa=(()=>{class n{static \u0275fac=function(t){return new(t||n)};static \u0275mod=bE({type:n});static \u0275inj=cu$1({imports:[Vs,et,jo$1]})}return n})();var Ot=(()=>{class n{static \u0275fac=function(t){return new(t||n)};static \u0275mod=bE({type:n});static \u0275inj=cu$1({imports:[jo$2,En,jo$1]})}return n})();function Bs(n){return Error(`Unable to find icon with the name "${n}"`)}function Ec(){return Error("Could not find HttpClient for use with Angular Material icons. Please add provideHttpClient() to your providers.")}function zs(n){return Error(`The URL provided to MatIconRegistry was not trusted as a resource URL via Angular's DomSanitizer. Attempted URL was "${n}".`)}function Hs(n){return Error(`The literal provided to MatIconRegistry was not trusted as safe HTML by Angular's DomSanitizer. Attempted literal was "${n}".`)}var Tt=class{url;svgText;options;svgElement=null;constructor(r,e,t){this.url=r,this.svgText=e,this.options=t;}},Gs=(()=>{class n{_httpClient;_sanitizer;_errorHandler;_document;_svgIconConfigs=new Map;_iconSetConfigs=new Map;_cachedIconsByUrl=new Map;_inProgressUrlFetches=new Map;_fontCssClassesByAlias=new Map;_resolvers=[];_defaultFontSetClass=["material-icons","mat-ligature-font"];constructor(e,t,i,a){this._httpClient=e,this._sanitizer=t,this._errorHandler=a,this._document=i;}addSvgIcon(e,t,i){return this.addSvgIconInNamespace("",e,t,i)}addSvgIconLiteral(e,t,i){return this.addSvgIconLiteralInNamespace("",e,t,i)}addSvgIconInNamespace(e,t,i,a){return this._addSvgIconConfig(e,t,new Tt(i,null,a))}addSvgIconResolver(e){return this._resolvers.push(e),this}addSvgIconLiteralInNamespace(e,t,i,a){let o=this._sanitizer.sanitize(Tr$2.HTML,i);if(!o)throw Hs(i);let c=_a$1(o);return this._addSvgIconConfig(e,t,new Tt("",c,a))}addSvgIconSet(e,t){return this.addSvgIconSetInNamespace("",e,t)}addSvgIconSetLiteral(e,t){return this.addSvgIconSetLiteralInNamespace("",e,t)}addSvgIconSetInNamespace(e,t,i){return this._addSvgIconSetConfig(e,new Tt(t,null,i))}addSvgIconSetLiteralInNamespace(e,t,i){let a=this._sanitizer.sanitize(Tr$2.HTML,t);if(!a)throw Hs(t);let o=_a$1(a);return this._addSvgIconSetConfig(e,new Tt("",o,i))}registerFontClassAlias(e,t=e){return this._fontCssClassesByAlias.set(e,t),this}classNameForFontAlias(e){return this._fontCssClassesByAlias.get(e)||e}setDefaultFontSetClass(...e){return this._defaultFontSetClass=e,this}getDefaultFontSetClass(){return this._defaultFontSetClass}getSvgIconFromUrl(e){let t=this._sanitizer.sanitize(Tr$2.RESOURCE_URL,e);if(!t)throw zs(e);let i=this._cachedIconsByUrl.get(t);return i?rg(An(i)):this._loadSvgIconFromConfig(new Tt(e,null)).pipe(Ul$1(a=>this._cachedIconsByUrl.set(t,a)),Ce(a=>An(a)))}getNamedSvgIcon(e,t=""){let i=js(t,e),a=this._svgIconConfigs.get(i);if(a)return this._getSvgFromConfig(a);if(a=this._getIconConfigFromResolvers(t,e),a)return this._svgIconConfigs.set(i,a),this._getSvgFromConfig(a);let o=this._iconSetConfigs.get(t);return o?this._getSvgFromIconSetConfigs(e,o):ug(Bs(i))}ngOnDestroy(){this._resolvers=[],this._svgIconConfigs.clear(),this._iconSetConfigs.clear(),this._cachedIconsByUrl.clear();}_getSvgFromConfig(e){return e.svgText?rg(An(this._svgElementFromConfig(e))):this._loadSvgIconFromConfig(e).pipe(Ce(t=>An(t)))}_getSvgFromIconSetConfigs(e,t){let i=this._extractIconWithNameFromAnySet(e,t);if(i)return rg(i);let a=t.filter(o=>!o.svgText).map(o=>this._loadSvgIconSetFromConfig(o).pipe(Ki(c=>{let F=`Loading icon set URL: ${this._sanitizer.sanitize(Tr$2.RESOURCE_URL,o.url)} failed: ${c.message}`;return this._errorHandler.handleError(new Error(F)),rg(null)})));return bg(a).pipe(Ce(()=>{let o=this._extractIconWithNameFromAnySet(e,t);if(!o)throw Bs(e);return o}))}_extractIconWithNameFromAnySet(e,t){for(let i=t.length-1;i>=0;i--){let a=t[i];if(a.svgText&&a.svgText.toString().indexOf(e)>-1){let o=this._svgElementFromConfig(a),c=this._extractSvgIconFromSet(o,e,a.options);if(c)return c}}return null}_loadSvgIconFromConfig(e){return this._fetchIcon(e).pipe(Ul$1(t=>e.svgText=t),Ce(()=>this._svgElementFromConfig(e)))}_loadSvgIconSetFromConfig(e){return e.svgText?rg(null):this._fetchIcon(e).pipe(Ul$1(t=>e.svgText=t))}_extractSvgIconFromSet(e,t,i){let a=e.querySelector(`[id="${t}"]`);if(!a)return null;let o=a.cloneNode(true);if(o.removeAttribute("id"),o.nodeName.toLowerCase()==="svg")return this._setSvgAttributes(o,i);if(o.nodeName.toLowerCase()==="symbol")return this._setSvgAttributes(this._toSvgElement(o),i);let c=this._svgElementFromString(_a$1("<svg></svg>"));return c.appendChild(o),this._setSvgAttributes(c,i)}_svgElementFromString(e){let t=this._document.createElement("DIV");t.innerHTML=e;let i=t.querySelector("svg");if(!i)throw Error("<svg> tag not found");return i}_toSvgElement(e){let t=this._svgElementFromString(_a$1("<svg></svg>")),i=e.attributes;for(let a=0;a<i.length;a++){let{name:o,value:c}=i[a];o!=="id"&&t.setAttribute(o,c);}for(let a=0;a<e.childNodes.length;a++)e.childNodes[a].nodeType===this._document.ELEMENT_NODE&&t.appendChild(e.childNodes[a].cloneNode(true));return t}_setSvgAttributes(e,t){return e.setAttribute("fit",""),e.setAttribute("height","100%"),e.setAttribute("width","100%"),e.setAttribute("preserveAspectRatio","xMidYMid meet"),e.setAttribute("focusable","false"),t&&t.viewBox&&e.setAttribute("viewBox",t.viewBox),e}_fetchIcon(e){let{url:t,options:i}=e,a=i?.withCredentials??false;if(!this._httpClient)throw Ec();if(t==null)throw Error(`Cannot fetch icon from URL "${t}".`);let o=this._sanitizer.sanitize(Tr$2.RESOURCE_URL,t);if(!o)throw zs(t);let c=this._inProgressUrlFetches.get(o);if(c)return c;let y=this._httpClient.get(o,{responseType:"text",withCredentials:a}).pipe(Ce(F=>_a$1(F)),Vl$1(()=>this._inProgressUrlFetches.delete(o)),es$1());return this._inProgressUrlFetches.set(o,y),y}_addSvgIconConfig(e,t,i){return this._svgIconConfigs.set(js(e,t),i),this}_addSvgIconSetConfig(e,t){let i=this._iconSetConfigs.get(e);return i?i.push(t):this._iconSetConfigs.set(e,[t]),this}_svgElementFromConfig(e){if(!e.svgElement){let t=this._svgElementFromString(e.svgText);this._setSvgAttributes(t,e.options),e.svgElement=t;}return e.svgElement}_getIconConfigFromResolvers(e,t){for(let i=0;i<this._resolvers.length;i++){let a=this._resolvers[i](t,e);if(a)return Tc(a)?new Tt(a.url,null,a.options):new Tt(a,null)}}static \u0275fac=function(t){return new(t||n)(Me(an,8),Me(Yn),Me(or$1,8),Me(We))};static \u0275prov=re({token:n,factory:n.\u0275fac,providedIn:"root"})}return n})();function An(n){return n.cloneNode(true)}function js(n,r){return n+":"+r}function Tc(n){return !!(n.url&&n.options)}var Ac=["*"],Rc=new x("MAT_ICON_DEFAULT_OPTIONS"),Fc=new x("mat-icon-location",{providedIn:"root",factory:()=>{let n=T(or$1),r=n?n.location:null;return {getPathname:()=>r?r.pathname+r.search:""}}}),Us=["clip-path","color-profile","src","cursor","fill","filter","marker","marker-start","marker-mid","marker-end","mask","stroke"],Oc=Us.map(n=>`[${n}]`).join(", "),Pc=/^url\(['"]?#(.*?)['"]?\)$/,Ws=(()=>{class n{_elementRef=T(Dr$1);_iconRegistry=T(Gs);_location=T(Fc);_errorHandler=T(We);_defaultColor;get color(){return this._color||this._defaultColor}set color(e){this._color=e;}_color;inline=false;get svgIcon(){return this._svgIcon}set svgIcon(e){e!==this._svgIcon&&(e?this._updateSvgIcon(e):this._svgIcon&&this._clearSvgElement(),this._svgIcon=e);}_svgIcon;get fontSet(){return this._fontSet}set fontSet(e){let t=this._cleanupFontValue(e);t!==this._fontSet&&(this._fontSet=t,this._updateFontIconClasses());}_fontSet;get fontIcon(){return this._fontIcon}set fontIcon(e){let t=this._cleanupFontValue(e);t!==this._fontIcon&&(this._fontIcon=t,this._updateFontIconClasses());}_fontIcon;_previousFontSetClass=[];_previousFontIconClass;_svgName=null;_svgNamespace=null;_previousPath;_elementsWithExternalReferences;_currentIconFetch=j.EMPTY;constructor(){let e=T(new Ah$1("aria-hidden"),{optional:true}),t=T(Rc,{optional:true});t&&(t.color&&(this.color=this._defaultColor=t.color),t.fontSet&&(this.fontSet=t.fontSet)),e||this._elementRef.nativeElement.setAttribute("aria-hidden","true");}_splitIconName(e){if(!e)return ["",""];let t=e.split(":");switch(t.length){case 1:return ["",t[0]];case 2:return t;default:throw Error(`Invalid icon name: "${e}"`)}}ngOnInit(){this._updateFontIconClasses();}ngAfterViewChecked(){let e=this._elementsWithExternalReferences;if(e&&e.size){let t=this._location.getPathname();t!==this._previousPath&&(this._previousPath=t,this._prependPathToReferences(t));}}ngOnDestroy(){this._currentIconFetch.unsubscribe(),this._elementsWithExternalReferences&&this._elementsWithExternalReferences.clear();}_usingFontIcon(){return !this.svgIcon}_setSvgElement(e){this._clearSvgElement();let t=this._location.getPathname();this._previousPath=t,this._cacheChildrenWithExternalReferences(e),this._prependPathToReferences(t),this._elementRef.nativeElement.appendChild(e);}_clearSvgElement(){let e=this._elementRef.nativeElement,t=e.childNodes.length;for(this._elementsWithExternalReferences&&this._elementsWithExternalReferences.clear();t--;){let i=e.childNodes[t];(i.nodeType!==1||i.nodeName.toLowerCase()==="svg")&&i.remove();}}_updateFontIconClasses(){if(!this._usingFontIcon())return;let e=this._elementRef.nativeElement,t=(this.fontSet?this._iconRegistry.classNameForFontAlias(this.fontSet).split(/ +/):this._iconRegistry.getDefaultFontSetClass()).filter(i=>i.length>0);this._previousFontSetClass.forEach(i=>e.classList.remove(i)),t.forEach(i=>e.classList.add(i)),this._previousFontSetClass=t,this.fontIcon!==this._previousFontIconClass&&!t.includes("mat-ligature-font")&&(this._previousFontIconClass&&e.classList.remove(this._previousFontIconClass),this.fontIcon&&e.classList.add(this.fontIcon),this._previousFontIconClass=this.fontIcon);}_cleanupFontValue(e){return typeof e=="string"?e.trim().split(" ")[0]:e}_prependPathToReferences(e){let t=this._elementsWithExternalReferences;t&&t.forEach((i,a)=>{i.forEach(o=>{a.setAttribute(o.name,`url('${e}#${o.value}')`);});});}_cacheChildrenWithExternalReferences(e){let t=e.querySelectorAll(Oc),i=this._elementsWithExternalReferences=this._elementsWithExternalReferences||new Map;for(let a=0;a<t.length;a++)Us.forEach(o=>{let c=t[a],y=c.getAttribute(o),F=y?y.match(Pc):null;if(F){let H=i.get(c);H||(H=[],i.set(c,H)),H.push({name:o,value:F[1]});}});}_updateSvgIcon(e){if(this._svgNamespace=null,this._svgName=null,this._currentIconFetch.unsubscribe(),e){let[t,i]=this._splitIconName(e);t&&(this._svgNamespace=t),i&&(this._svgName=i),this._currentIconFetch=this._iconRegistry.getNamedSvgIcon(i,t).pipe(tn(1)).subscribe(a=>this._setSvgElement(a),a=>{let o=`Error retrieving icon ${t}:${i}! ${a.message}`;this._errorHandler.handleError(new Error(o));});}}static \u0275fac=function(t){return new(t||n)};static \u0275cmp=TE({type:n,selectors:[["mat-icon"]],hostAttrs:["role","img",1,"mat-icon","notranslate"],hostVars:10,hostBindings:function(t,i){t&2&&(Qp("data-mat-icon-type",i._usingFontIcon()?"font":"svg")("data-mat-icon-name",i._svgName||i.fontIcon)("data-mat-icon-namespace",i._svgNamespace||i.fontSet)("fontIcon",i._usingFontIcon()?i.fontIcon:null),RD(i.color?"mat-"+i.color:""),fh$1("mat-icon-inline",i.inline)("mat-icon-no-color",i.color!=="primary"&&i.color!=="accent"&&i.color!=="warn"));},inputs:{color:"color",inline:[2,"inline","inline",j0],svgIcon:"svgIcon",fontSet:"fontSet",fontIcon:"fontIcon"},exportAs:["matIcon"],ngContentSelectors:Ac,decls:1,vars:0,template:function(t,i){t&1&&(gD(),mD(0));},styles:[`mat-icon, mat-icon.mat-primary, mat-icon.mat-accent, mat-icon.mat-warn {
  color: var(--mat-icon-color, inherit);
}

.mat-icon {
  -webkit-user-select: none;
  user-select: none;
  background-repeat: no-repeat;
  display: inline-block;
  fill: currentColor;
  height: 24px;
  width: 24px;
  overflow: hidden;
}
.mat-icon.mat-icon-inline {
  font-size: inherit;
  height: inherit;
  line-height: inherit;
  width: inherit;
}
.mat-icon.mat-ligature-font[fontIcon]::before {
  content: attr(fontIcon);
}

[dir=rtl] .mat-icon-rtl-mirror {
  transform: scale(-1, 1);
}

.mat-form-field:not(.mat-form-field-appearance-legacy) .mat-form-field-prefix .mat-icon,
.mat-form-field:not(.mat-form-field-appearance-legacy) .mat-form-field-suffix .mat-icon {
  display: block;
}
.mat-form-field:not(.mat-form-field-appearance-legacy) .mat-form-field-prefix .mat-icon-button .mat-icon,
.mat-form-field:not(.mat-form-field-appearance-legacy) .mat-form-field-suffix .mat-icon-button .mat-icon {
  margin: auto;
}
`],encapsulation:2})}return n})(),Pi=(()=>{class n{static \u0275fac=function(t){return new(t||n)};static \u0275mod=bE({type:n});static \u0275inj=cu$1({imports:[jo$1]})}return n})();var Vc=(()=>{class n{static \u0275fac=function(t){return new(t||n)};static \u0275cmp=TE({type:n,selectors:[["ng-component"]],hostAttrs:["cdk-text-field-style-loader",""],decls:0,vars:0,template:function(t,i){},styles:[`textarea.cdk-textarea-autosize {
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
`],encapsulation:2})}return n})(),Lc={passive:true},qs=(()=>{class n{_platform=T(D);_ngZone=T(Se);_renderer=T(gr$1).createRenderer(null,null);_styleLoader=T(W$1);_monitoredElements=new Map;monitor(e){if(!this._platform.isBrowser)return Dt;this._styleLoader.load(Vc);let t=K(e),i=this._monitoredElements.get(t);if(i)return i.subject;let a=new ne,o="cdk-text-field-autofilled",c=F=>{F.animationName==="cdk-text-field-autofill-start"&&!t.classList.contains(o)?(t.classList.add(o),this._ngZone.run(()=>a.next({target:F.target,isAutofilled:true}))):F.animationName==="cdk-text-field-autofill-end"&&t.classList.contains(o)&&(t.classList.remove(o),this._ngZone.run(()=>a.next({target:F.target,isAutofilled:false})));},y=this._ngZone.runOutsideAngular(()=>(t.classList.add("cdk-text-field-autofill-monitored"),this._renderer.listen(t,"animationstart",c,Lc)));return this._monitoredElements.set(t,{subject:a,unlisten:y}),a}stopMonitoring(e){let t=K(e),i=this._monitoredElements.get(t);i&&(i.unlisten(),i.subject.complete(),t.classList.remove("cdk-text-field-autofill-monitored"),t.classList.remove("cdk-text-field-autofilled"),this._monitoredElements.delete(t));}ngOnDestroy(){this._monitoredElements.forEach((e,t)=>this.stopMonitoring(t));}static \u0275fac=function(t){return new(t||n)};static \u0275prov=Er$1({token:n,factory:n.\u0275fac})}return n})();var Qs=(()=>{class n{static \u0275fac=function(t){return new(t||n)};static \u0275mod=bE({type:n});static \u0275inj=cu$1({})}return n})();var Ys=new x("MAT_INPUT_VALUE_ACCESSOR");var Nc=["button","checkbox","file","hidden","image","radio","range","reset","submit"],Bc=new x("MAT_INPUT_CONFIG"),Gg=(()=>{class n{_elementRef=T(Dr$1);_platform=T(D);ngControl=T(lt,{optional:true,self:true});_autofillMonitor=T(qs);_ngZone=T(Se);_formField=T(Wt,{optional:true});_renderer=T(MI);_uid=T(Y).getId("mat-input-");_previousNativeValue;_inputValueAccessor;_signalBasedValueAccessor;_previousPlaceholder=null;_errorStateTracker;_config=T(Bc,{optional:true});_cleanupIosKeyup;_cleanupWebkitWheel;_isServer=false;_isNativeSelect=false;_isTextarea=false;_isInFormField=false;focused=false;stateChanges=new ne;controlType="mat-input";autofilled=false;get disabled(){return this._disabled}set disabled(e){this._disabled=mm$1(e),this.focused&&(this.focused=false,this.stateChanges.next());}_disabled=false;get id(){return this._id}set id(e){this._id=e||this._uid;}_id;placeholder;name;get required(){return this._required??this.ngControl?.control?.hasValidator(Gt.required)??false}set required(e){this._required=mm$1(e);}_required;get type(){return this._type}set type(e){this._type=e||"text",this._validateType(),!this._isTextarea&&rm$1().has(this._type)&&(this._elementRef.nativeElement.type=this._type);}_type="text";get errorStateMatcher(){return this._errorStateTracker.matcher}set errorStateMatcher(e){this._errorStateTracker.matcher=e;}userAriaDescribedBy;get value(){return this._signalBasedValueAccessor?this._signalBasedValueAccessor.value():this._inputValueAccessor.value}set value(e){e!==this.value&&(this._signalBasedValueAccessor?this._signalBasedValueAccessor.value.set(e):this._inputValueAccessor.value=e,this.stateChanges.next());}get readonly(){return this._readonly}set readonly(e){this._readonly=mm$1(e);}_readonly=false;disabledInteractive;get errorState(){return this._errorStateTracker.errorState}set errorState(e){this._errorStateTracker.errorState=e;}_neverEmptyInputTypes=["date","datetime","datetime-local","month","time","week"].filter(e=>rm$1().has(e));constructor(){let e=T(Ti,{optional:true}),t=T(Ai,{optional:true}),i=T(nf),a=T(Ys,{optional:true,self:true}),o=this._elementRef.nativeElement,c=o.nodeName.toLowerCase();a?$o$1(a.value)?this._signalBasedValueAccessor=a:this._inputValueAccessor=a:this._inputValueAccessor=o,this._previousNativeValue=this.value,this.id=this.id,this._platform.IOS&&this._ngZone.runOutsideAngular(()=>{this._cleanupIosKeyup=this._renderer.listen(o,"keyup",this._iOSKeyupListener);}),this._errorStateTracker=new Hr$1(i,this.ngControl,t,e,this.stateChanges),this._isServer=!this._platform.isBrowser,this._isNativeSelect=c==="select",this._isTextarea=c==="textarea",this._isInFormField=!!this._formField,this.disabledInteractive=this._config?.disabledInteractive||false,this._isNativeSelect&&(this.controlType=o.multiple?"mat-native-select-multiple":"mat-native-select"),this._signalBasedValueAccessor&&nd$1(()=>{this._signalBasedValueAccessor.value(),this.stateChanges.next();});}ngAfterViewInit(){this._platform.isBrowser&&this._autofillMonitor.monitor(this._elementRef.nativeElement).subscribe(e=>{this.autofilled=e.isAutofilled,this.stateChanges.next();});}ngOnChanges(){this.stateChanges.next();}ngOnDestroy(){this.stateChanges.complete(),this._platform.isBrowser&&this._autofillMonitor.stopMonitoring(this._elementRef.nativeElement),this._cleanupIosKeyup?.(),this._cleanupWebkitWheel?.();}ngDoCheck(){this.ngControl&&(this.updateErrorState(),this.ngControl.disabled!==null&&this.ngControl.disabled!==this.disabled&&(this.disabled=this.ngControl.disabled,this.stateChanges.next())),this._dirtyCheckNativeValue(),this._dirtyCheckPlaceholder();}focus(e){this._elementRef.nativeElement.focus(e);}updateErrorState(){this._errorStateTracker.updateErrorState();}_focusChanged(e){if(e!==this.focused){if(!this._isNativeSelect&&e&&this.disabled&&this.disabledInteractive){let t=this._elementRef.nativeElement;t.type==="number"?(t.type="text",t.setSelectionRange(0,0),t.type="number"):t.setSelectionRange(0,0);}this.focused=e,this.stateChanges.next();}}_onInput(){}_dirtyCheckNativeValue(){let e=this._elementRef.nativeElement.value;this._previousNativeValue!==e&&(this._previousNativeValue=e,this.stateChanges.next());}_dirtyCheckPlaceholder(){let e=this._getPlaceholder();if(e!==this._previousPlaceholder){let t=this._elementRef.nativeElement;this._previousPlaceholder=e,e?t.setAttribute("placeholder",e):t.removeAttribute("placeholder");}}_getPlaceholder(){return this.placeholder||null}_validateType(){Nc.indexOf(this._type)>-1;}_isNeverEmpty(){return this._neverEmptyInputTypes.indexOf(this._type)>-1}_isBadInput(){let e=this._elementRef.nativeElement.validity;return e&&e.badInput}get empty(){return !this._isNeverEmpty()&&!this._elementRef.nativeElement.value&&!this._isBadInput()&&!this.autofilled}get shouldLabelFloat(){if(this._isNativeSelect){let e=this._elementRef.nativeElement,t=e.options[0];return this.focused||e.multiple||!this.empty||!!(e.selectedIndex>-1&&t&&t.label)}else return this.focused&&!this.disabled||!this.empty}get describedByIds(){return this._elementRef.nativeElement.getAttribute("aria-describedby")?.split(" ")||[]}setDescribedByIds(e){let t=this._elementRef.nativeElement;e.length?t.setAttribute("aria-describedby",e.join(" ")):t.removeAttribute("aria-describedby");}onContainerClick(){this.focused||this.focus();}_isInlineSelect(){let e=this._elementRef.nativeElement;return this._isNativeSelect&&(e.multiple||e.size>1)}_iOSKeyupListener=e=>{let t=e.target;!t.value&&t.selectionStart===0&&t.selectionEnd===0&&(t.setSelectionRange(1,1),t.setSelectionRange(0,0));};_getReadonlyAttribute(){return this._isNativeSelect?null:this.readonly||this.disabled&&this.disabledInteractive?"true":null}static \u0275fac=function(t){return new(t||n)};static \u0275dir=SE({type:n,selectors:[["input","matInput",""],["textarea","matInput",""],["select","matNativeControl",""],["input","matNativeControl",""],["textarea","matNativeControl",""]],hostAttrs:[1,"mat-mdc-input-element"],hostVars:21,hostBindings:function(t,i){t&1&&nh$1("focus",function(){return i._focusChanged(true)})("blur",function(){return i._focusChanged(false)})("input",function(){return i._onInput()}),t&2&&(eh$1("id",i.id)("disabled",i.disabled&&!i.disabledInteractive)("required",i.required),Qp("name",i.name||null)("readonly",i._getReadonlyAttribute())("aria-disabled",i.disabled&&i.disabledInteractive?"true":null)("aria-invalid",i.empty&&i.required?null:i.errorState)("aria-required",i.required)("id",i.id),fh$1("mat-input-server",i._isServer)("mat-mdc-form-field-textarea-control",i._isInFormField&&i._isTextarea)("mat-mdc-form-field-input-control",i._isInFormField)("mat-mdc-input-disabled-interactive",i.disabledInteractive)("mdc-text-field__input",i._isInFormField)("mat-mdc-native-select-inline",i._isInlineSelect()));},inputs:{disabled:"disabled",id:"id",placeholder:"placeholder",name:"name",required:"required",type:"type",errorStateMatcher:"errorStateMatcher",userAriaDescribedBy:[0,"aria-describedby","userAriaDescribedBy"],value:"value",readonly:"readonly",disabledInteractive:[2,"disabledInteractive","disabledInteractive",j0]},exportAs:["matInput"],features:[JD([{provide:Ri,useExisting:n}]),zm$1]})}return n})(),Va=(()=>{class n{static \u0275fac=function(t){return new(t||n)};static \u0275mod=bE({type:n});static \u0275inj=cu$1({imports:[Ot,Ot,Qs,jo$1]})}return n})();var zc=[[["mat-icon"],["","matMenuItemIcon",""]],"*"],Hc=["mat-icon, [matMenuItemIcon]","*"];function jc(n,r){n&1&&(Gu(),yi(0,"svg",2),Yp(1,"polygon",3),jc$1());}var Gc=["*"];function Uc(n,r){if(n&1){let e=iD();Vc$1(0,"div",0),rh$1("click",function(){ku(e);let i=pD();return Ou(i.closed.emit("click"))})("animationstart",function(i){ku(e);let a=pD();return Ou(a._onAnimationStart(i.animationName))})("animationend",function(i){ku(e);let a=pD();return Ou(a._onAnimationDone(i.animationName))})("animationcancel",function(i){ku(e);let a=pD();return Ou(a._onAnimationDone(i.animationName))}),Vc$1(1,"div",1),mD(2),Hc$1()();}if(n&2){let e=pD();RD(e._classList),fh$1("mat-menu-panel-animations-disabled",e._animationsDisabled)("mat-menu-panel-exit-animation",e._panelAnimationState==="void")("mat-menu-panel-animating",e._isAnimating()),eh$1("id",e.panelId),Qp("aria-label",e.ariaLabel||null)("aria-labelledby",e.ariaLabelledby||null)("aria-describedby",e.ariaDescribedby||null);}}var Ba=new x("MAT_MENU_PANEL"),Na=(()=>{class n{_elementRef=T(Dr$1);_document=T(or$1);_focusMonitor=T(le);_parentMenu=T(Ba,{optional:true});_changeDetectorRef=T(L0);role="menuitem";disabled=false;disableRipple=false;_hovered=new ne;_focused=new ne;_highlighted=false;_triggersSubmenu=false;constructor(){T(W$1).load(bn$1),this._parentMenu?.addItem?.(this);}focus(e,t){this._focusMonitor&&e?this._focusMonitor.focusVia(this._getHostElement(),e,t):this._getHostElement().focus(t),this._focused.next(this);}ngAfterViewInit(){this._focusMonitor&&this._focusMonitor.monitor(this._elementRef,false);}ngOnDestroy(){this._focusMonitor&&this._focusMonitor.stopMonitoring(this._elementRef),this._parentMenu&&this._parentMenu.removeItem&&this._parentMenu.removeItem(this),this._hovered.complete(),this._focused.complete();}_getTabIndex(){return this.disabled?"-1":"0"}_getHostElement(){return this._elementRef.nativeElement}_checkDisabled(e){this.disabled&&(e.preventDefault(),e.stopPropagation());}_handleMouseEnter(){this._hovered.next(this);}getLabel(){let e=this._elementRef.nativeElement.cloneNode(true),t=e.querySelectorAll("mat-icon, .material-icons");for(let i=0;i<t.length;i++)t[i].remove();return e.textContent?.trim()||""}_setHighlighted(e){this._highlighted=e,this._changeDetectorRef.markForCheck();}_setTriggersSubmenu(e){this._triggersSubmenu=e,this._changeDetectorRef.markForCheck();}_hasFocus(){return this._document&&this._document.activeElement===this._getHostElement()}static \u0275fac=function(t){return new(t||n)};static \u0275cmp=TE({type:n,selectors:[["","mat-menu-item",""]],hostAttrs:[1,"mat-mdc-menu-item","mat-focus-indicator"],hostVars:8,hostBindings:function(t,i){t&1&&nh$1("click",function(o){return i._checkDisabled(o)})("mouseenter",function(){return i._handleMouseEnter()}),t&2&&(Qp("role",i.role)("tabindex",i._getTabIndex())("aria-disabled",i.disabled)("disabled",i.disabled||null),fh$1("mat-mdc-menu-item-highlighted",i._highlighted)("mat-mdc-menu-item-submenu-trigger",i._triggersSubmenu));},inputs:{role:"role",disabled:[2,"disabled","disabled",j0],disableRipple:[2,"disableRipple","disableRipple",j0]},exportAs:["matMenuItem"],ngContentSelectors:Hc,decls:5,vars:3,consts:[[1,"mat-mdc-menu-item-text"],["matRipple","",1,"mat-mdc-menu-ripple",3,"matRippleDisabled","matRippleTrigger"],["viewBox","0 0 5 10","focusable","false","aria-hidden","true",1,"mat-mdc-menu-submenu-icon"],["points","0,0 5,5 0,10"]],template:function(t,i){t&1&&(gD(zc),mD(0),yi(1,"span",0),mD(2,1),jc$1(),Yp(3,"div",1),QE(4,jc,2,0,":svg:svg",2)),t&2&&(Wv(3),Zp("matRippleDisabled",i.disableRipple||i.disabled)("matRippleTrigger",i._getHostElement()),Wv(),ZE(i._triggersSubmenu?4:-1));},dependencies:[rr$1],encapsulation:2})}return n})();var Wc=new x("MatMenuContent");var qc=new x("mat-menu-default-options",{providedIn:"root",factory:()=>({overlapTrigger:false,xPosition:"after",yPosition:"below",backdropClass:"cdk-overlay-transparent-backdrop"})}),La="_mat-menu-enter",Rn="_mat-menu-exit",Fn=(()=>{class n{_elementRef=T(Dr$1);_changeDetectorRef=T(L0);_injector=T(ge);_keyManager;_xPosition;_yPosition;_firstItemFocusRef;_exitFallbackTimeout;_animationsDisabled=B();_allItems;_directDescendantItems=new ri;_classList={};_panelAnimationState="void";_animationDone=new ne;_isAnimating=Vo$1(false);parentMenu;direction;overlayPanelClass;backdropClass;ariaLabel;ariaLabelledby;ariaDescribedby;get xPosition(){return this._xPosition}set xPosition(e){this._xPosition=e,this.setPositionClasses();}get yPosition(){return this._yPosition}set yPosition(e){this._yPosition=e,this.setPositionClasses();}templateRef;items;lazyContent;overlapTrigger=false;hasBackdrop;get panelClass(){return this._previousPanelClass}set panelClass(e){let t=this._previousPanelClass,i=q({},this._classList);t&&t.length&&t.split(" ").forEach(a=>{i[a]=false;}),this._previousPanelClass=e,e&&e.length&&(e.split(" ").forEach(a=>{i[a]=true;}),this._elementRef.nativeElement.className=""),this._classList=i;}_previousPanelClass="";get classList(){return this.panelClass}set classList(e){this.panelClass=e;}closed=new $e;close=this.closed;panelId=T(Y).getId("mat-menu-panel-");constructor(){let e=T(qc);this.overlayPanelClass=e.overlayPanelClass||"",this._xPosition=e.xPosition,this._yPosition=e.yPosition,this.backdropClass=e.backdropClass,this.overlapTrigger=e.overlapTrigger,this.hasBackdrop=e.hasBackdrop;}ngOnInit(){this.setPositionClasses();}ngAfterContentInit(){this._updateDirectDescendants(),this._keyManager=new ni(this._directDescendantItems).withWrap().withTypeAhead().withHomeAndEnd(),this._keyManager.tabOut.subscribe(()=>this.closed.emit("tab")),this._directDescendantItems.changes.pipe(Hl$1(this._directDescendantItems),Cl$1(e=>kg(...e.map(t=>t._focused)))).subscribe(e=>this._keyManager.updateActiveItem(e)),this._directDescendantItems.changes.subscribe(e=>{let t=this._keyManager;if(this._panelAnimationState==="enter"&&t.activeItem?._hasFocus()){let i=e.toArray(),a=Math.max(0,Math.min(i.length-1,t.activeItemIndex||0));i[a]&&!i[a].disabled?t.setActiveItem(a):t.setNextItemActive();}});}ngOnDestroy(){this._keyManager?.destroy(),this._directDescendantItems.destroy(),this.closed.complete(),this._firstItemFocusRef?.destroy(),clearTimeout(this._exitFallbackTimeout);}_hovered(){return this._directDescendantItems.changes.pipe(Hl$1(this._directDescendantItems),Cl$1(t=>kg(...t.map(i=>i._hovered))))}addItem(e){}removeItem(e){}_handleKeydown(e){let t=e.keyCode,i=this._keyManager;switch(t){case 27:$(e)||(e.preventDefault(),this.closed.emit("keydown"));break;case 37:this.parentMenu&&this.direction==="ltr"&&this.closed.emit("keydown");break;case 39:this.parentMenu&&this.direction==="rtl"&&this.closed.emit("keydown");break;default:(t===38||t===40)&&i.setFocusOrigin("keyboard"),i.onKeydown(e);return}}focusFirstItem(e="program"){this._firstItemFocusRef?.destroy(),this._firstItemFocusRef=Dv(()=>{let t=this._resolvePanel();if(!t||!t.contains(document.activeElement)){let i=this._keyManager;i.setFocusOrigin(e).setFirstItemActive(),!i.activeItem&&t&&t.focus();}},{injector:this._injector});}resetActiveItem(){this._keyManager.setActiveItem(-1);}setElevation(e){}setPositionClasses(e=this.xPosition,t=this.yPosition){this._classList=W(q({},this._classList),{"mat-menu-before":e==="before","mat-menu-after":e==="after","mat-menu-above":t==="above","mat-menu-below":t==="below"}),this._changeDetectorRef.markForCheck();}_onAnimationDone(e){let t=e===Rn;(t||e===La)&&(t&&(clearTimeout(this._exitFallbackTimeout),this._exitFallbackTimeout=void 0),this._animationDone.next(t?"void":"enter"),this._isAnimating.set(false));}_onAnimationStart(e){(e===La||e===Rn)&&this._isAnimating.set(true);}_setIsOpen(e){if(this._panelAnimationState=e?"enter":"void",e){if(this._keyManager.activeItemIndex===0){let t=this._resolvePanel();t&&(t.scrollTop=0);}}else this._animationsDisabled||(this._exitFallbackTimeout=setTimeout(()=>this._onAnimationDone(Rn),200));this._animationsDisabled&&setTimeout(()=>{this._onAnimationDone(e?La:Rn);}),this._changeDetectorRef.markForCheck();}_updateDirectDescendants(){this._allItems.changes.pipe(Hl$1(this._allItems)).subscribe(e=>{this._directDescendantItems.reset(e.filter(t=>t._parentMenu===this)),this._directDescendantItems.notifyOnChanges();});}_resolvePanel(){let e=null;return this._directDescendantItems.length&&(e=this._directDescendantItems.first._getHostElement().closest('[role="menu"]')),e}static \u0275fac=function(t){return new(t||n)};static \u0275cmp=TE({type:n,selectors:[["mat-menu"]],contentQueries:function(t,i,a){if(t&1&&ih$1(a,Wc,5)(a,Na,5)(a,Na,4),t&2){let o;vD(o=ID())&&(i.lazyContent=o.first),vD(o=ID())&&(i._allItems=o),vD(o=ID())&&(i.items=o);}},viewQuery:function(t,i){if(t&1&&sh$1(hr$1,5),t&2){let a;vD(a=ID())&&(i.templateRef=a.first);}},hostVars:3,hostBindings:function(t,i){t&2&&Qp("aria-label",null)("aria-labelledby",null)("aria-describedby",null);},inputs:{backdropClass:"backdropClass",ariaLabel:[0,"aria-label","ariaLabel"],ariaLabelledby:[0,"aria-labelledby","ariaLabelledby"],ariaDescribedby:[0,"aria-describedby","ariaDescribedby"],xPosition:"xPosition",yPosition:"yPosition",overlapTrigger:[2,"overlapTrigger","overlapTrigger",j0],hasBackdrop:[2,"hasBackdrop","hasBackdrop",e=>e==null?null:j0(e)],panelClass:[0,"class","panelClass"],classList:"classList"},outputs:{closed:"closed",close:"close"},exportAs:["matMenu"],features:[JD([{provide:Ba,useExisting:n}])],ngContentSelectors:Gc,decls:1,vars:0,consts:[["tabindex","-1","role","menu",1,"mat-mdc-menu-panel",3,"click","animationstart","animationend","animationcancel","id"],[1,"mat-mdc-menu-content"]],template:function(t,i){t&1&&(gD(),Up(0,Uc,3,12,"ng-template"));},styles:[`mat-menu {
  display: none;
}

.mat-mdc-menu-content {
  margin: 0;
  padding: 8px 0;
  outline: 0;
}
.mat-mdc-menu-content,
.mat-mdc-menu-content .mat-mdc-menu-item .mat-mdc-menu-item-text {
  -moz-osx-font-smoothing: grayscale;
  -webkit-font-smoothing: antialiased;
  flex: 1;
  white-space: normal;
  font-family: var(--mat-menu-item-label-text-font, var(--mat-sys-label-large-font));
  line-height: var(--mat-menu-item-label-text-line-height, var(--mat-sys-label-large-line-height));
  font-size: var(--mat-menu-item-label-text-size, var(--mat-sys-label-large-size));
  letter-spacing: var(--mat-menu-item-label-text-tracking, var(--mat-sys-label-large-tracking));
  font-weight: var(--mat-menu-item-label-text-weight, var(--mat-sys-label-large-weight));
}

@keyframes _mat-menu-enter {
  from {
    opacity: 0;
    transform: scale(0.8);
  }
  to {
    opacity: 1;
    transform: none;
  }
}
@keyframes _mat-menu-exit {
  from {
    opacity: 1;
  }
  to {
    opacity: 0;
  }
}
.mat-mdc-menu-panel {
  min-width: 112px;
  max-width: 280px;
  overflow: auto;
  box-sizing: border-box;
  outline: 0;
  animation: _mat-menu-enter 120ms cubic-bezier(0, 0, 0.2, 1);
  border-radius: var(--mat-menu-container-shape, var(--mat-sys-corner-extra-small));
  background-color: var(--mat-menu-container-color, var(--mat-sys-surface-container));
  box-shadow: var(--mat-menu-container-elevation-shadow, 0px 3px 1px -2px rgba(0, 0, 0, 0.2), 0px 2px 2px 0px rgba(0, 0, 0, 0.14), 0px 1px 5px 0px rgba(0, 0, 0, 0.12));
  will-change: transform, opacity;
}
.mat-mdc-menu-panel.mat-menu-panel-exit-animation {
  animation: _mat-menu-exit 100ms 25ms linear forwards;
}
.mat-mdc-menu-panel.mat-menu-panel-animations-disabled {
  animation: none;
}
.mat-mdc-menu-panel.mat-menu-panel-animating {
  pointer-events: none;
}
.mat-mdc-menu-panel.mat-menu-panel-animating:has(.mat-mdc-menu-content:empty) {
  display: none;
}
@media (forced-colors: active) {
  .mat-mdc-menu-panel {
    outline: solid 1px;
  }
}
.mat-mdc-menu-panel .mat-divider {
  border-top-color: var(--mat-menu-divider-color, var(--mat-sys-surface-variant));
  margin-bottom: var(--mat-menu-divider-bottom-spacing, 8px);
  margin-top: var(--mat-menu-divider-top-spacing, 8px);
}

.mat-mdc-menu-item {
  display: flex;
  position: relative;
  align-items: center;
  justify-content: flex-start;
  overflow: hidden;
  padding: 0;
  cursor: pointer;
  width: 100%;
  text-align: left;
  box-sizing: border-box;
  color: inherit;
  font-size: inherit;
  background: none;
  text-decoration: none;
  margin: 0;
  min-height: 48px;
  padding-left: var(--mat-menu-item-leading-spacing, 12px);
  padding-right: var(--mat-menu-item-trailing-spacing, 12px);
  -webkit-user-select: none;
  user-select: none;
  cursor: pointer;
  outline: none;
  border: none;
  -webkit-tap-highlight-color: transparent;
}
.mat-mdc-menu-item::-moz-focus-inner {
  border: 0;
}
[dir=rtl] .mat-mdc-menu-item {
  padding-left: var(--mat-menu-item-trailing-spacing, 12px);
  padding-right: var(--mat-menu-item-leading-spacing, 12px);
}
.mat-mdc-menu-item:has(.material-icons, mat-icon, [matButtonIcon]) {
  padding-left: var(--mat-menu-item-with-icon-leading-spacing, 12px);
  padding-right: var(--mat-menu-item-with-icon-trailing-spacing, 12px);
}
[dir=rtl] .mat-mdc-menu-item:has(.material-icons, mat-icon, [matButtonIcon]) {
  padding-left: var(--mat-menu-item-with-icon-trailing-spacing, 12px);
  padding-right: var(--mat-menu-item-with-icon-leading-spacing, 12px);
}
.mat-mdc-menu-item, .mat-mdc-menu-item:visited, .mat-mdc-menu-item:link {
  color: var(--mat-menu-item-label-text-color, var(--mat-sys-on-surface));
}
.mat-mdc-menu-item .mat-icon-no-color,
.mat-mdc-menu-item .mat-mdc-menu-submenu-icon {
  color: var(--mat-menu-item-icon-color, var(--mat-sys-on-surface-variant));
}
.mat-mdc-menu-item[disabled] {
  cursor: default;
  opacity: 0.38;
}
.mat-mdc-menu-item[disabled]::after {
  display: block;
  position: absolute;
  content: "";
  top: 0;
  left: 0;
  bottom: 0;
  right: 0;
}
.mat-mdc-menu-item:focus {
  outline: 0;
}
.mat-mdc-menu-item .mat-icon {
  flex-shrink: 0;
  margin-right: var(--mat-menu-item-spacing, 12px);
  height: var(--mat-menu-item-icon-size, 24px);
  width: var(--mat-menu-item-icon-size, 24px);
}
[dir=rtl] .mat-mdc-menu-item {
  text-align: right;
}
[dir=rtl] .mat-mdc-menu-item .mat-icon {
  margin-right: 0;
  margin-left: var(--mat-menu-item-spacing, 12px);
}
.mat-mdc-menu-item:not([disabled]):hover {
  background-color: var(--mat-menu-item-hover-state-layer-color, color-mix(in srgb, var(--mat-sys-on-surface) calc(var(--mat-sys-hover-state-layer-opacity) * 100%), transparent));
}
.mat-mdc-menu-item:not([disabled]).cdk-program-focused, .mat-mdc-menu-item:not([disabled]).cdk-keyboard-focused, .mat-mdc-menu-item:not([disabled]).mat-mdc-menu-item-highlighted {
  background-color: var(--mat-menu-item-focus-state-layer-color, color-mix(in srgb, var(--mat-sys-on-surface) calc(var(--mat-sys-focus-state-layer-opacity) * 100%), transparent));
}
@media (forced-colors: active) {
  .mat-mdc-menu-item {
    margin-top: 1px;
  }
}

.mat-mdc-menu-submenu-icon {
  width: var(--mat-menu-item-icon-size, 24px);
  height: 10px;
  fill: currentColor;
  padding-left: var(--mat-menu-item-spacing, 12px);
}
[dir=rtl] .mat-mdc-menu-submenu-icon {
  padding-right: var(--mat-menu-item-spacing, 12px);
  padding-left: 0;
}
[dir=rtl] .mat-mdc-menu-submenu-icon polygon {
  transform: scaleX(-1);
  transform-origin: center;
}
@media (forced-colors: active) {
  .mat-mdc-menu-submenu-icon {
    fill: CanvasText;
  }
}

.mat-mdc-menu-item .mat-mdc-menu-ripple {
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  position: absolute;
  pointer-events: none;
}
`],encapsulation:2})}return n})(),Qc=new x("mat-menu-scroll-strategy",{providedIn:"root",factory:()=>{let n=T(ge);return ()=>gi$1(n)}});var _i=new WeakMap,Yc=(()=>{class n{_canHaveBackdrop;_element=T(Dr$1);_viewContainerRef=T(Ai$1);_menuItemInstance=T(Na,{optional:true,self:true});_dir=T(Er$2,{optional:true});_focusMonitor=T(le);_ngZone=T(Se);_injector=T(ge);_scrollStrategy=T(Qc);_changeDetectorRef=T(L0);_animationsDisabled=B();_portal;_overlayRef=null;_menuOpen=false;_closingActionsSubscription=j.EMPTY;_menuCloseSubscription=j.EMPTY;_pendingRemoval;_parentMaterialMenu;_parentInnerPadding;_openedBy=void 0;get _menu(){return this._menuInternal}set _menu(e){e!==this._menuInternal&&(this._menuInternal=e,this._menuCloseSubscription.unsubscribe(),e?(this._parentMaterialMenu,this._menuCloseSubscription=e.close.subscribe(t=>{this._destroyMenu(t),(t==="click"||t==="tab")&&this._parentMaterialMenu&&this._parentMaterialMenu.closed.emit(t);})):this._destroyMenu(),this._menuItemInstance?._setTriggersSubmenu(this._triggersSubmenu()));}_menuInternal=null;constructor(e){this._canHaveBackdrop=e;let t=T(Ba,{optional:true});this._parentMaterialMenu=t instanceof Fn?t:void 0;}ngOnDestroy(){this._menu&&this._ownsMenu(this._menu)&&_i.delete(this._menu),this._pendingRemoval?.unsubscribe(),this._menuCloseSubscription.unsubscribe(),this._closingActionsSubscription.unsubscribe(),this._overlayRef&&(this._overlayRef.dispose(),this._overlayRef=null);}get menuOpen(){return this._menuOpen}get dir(){return this._dir&&this._dir.value==="rtl"?"rtl":"ltr"}_triggersSubmenu(){return !!(this._menuItemInstance&&this._parentMaterialMenu&&this._menu)}_closeMenu(){this._menu?.close.emit();}_openMenu(e){if(this._triggerIsAriaDisabled())return;let t=this._menu;if(this._menuOpen||!t)return;this._pendingRemoval?.unsubscribe();let i=_i.get(t);_i.set(t,this),i&&i!==this&&i._closeMenu();let a=this._createOverlay(t),o=a.getConfig(),c=o.positionStrategy;this._setPosition(t,c),this._canHaveBackdrop?o.hasBackdrop=t.hasBackdrop==null?!this._triggersSubmenu():t.hasBackdrop:o.hasBackdrop=t.hasBackdrop??false,a.hasAttached()||(a.attach(this._getPortal(t)),t.lazyContent?.attach(this.menuData)),this._closingActionsSubscription=this._menuClosingActions().subscribe(()=>this._closeMenu()),t.parentMenu=this._triggersSubmenu()?this._parentMaterialMenu:void 0,t.direction=this.dir,e&&t.focusFirstItem(this._openedBy||"program"),this._setIsMenuOpen(true),t instanceof Fn&&(t._setIsOpen(true),t._directDescendantItems.changes.pipe(Bl$1(t.close)).subscribe(()=>{c.withLockedPosition(false).reapplyLastPosition(),c.withLockedPosition(true);}));}focus(e,t){this._focusMonitor&&e?this._focusMonitor.focusVia(this._element,e,t):this._element.nativeElement.focus(t);}_destroyMenu(e){let t=this._overlayRef,i=this._menu;!t||!this.menuOpen||(this._closingActionsSubscription.unsubscribe(),this._pendingRemoval?.unsubscribe(),i instanceof Fn&&this._ownsMenu(i)?(this._pendingRemoval=i._animationDone.pipe(tn(1)).subscribe(()=>{t.detach(),_i.has(i)||i.lazyContent?.detach();}),i._setIsOpen(false)):(t.detach(),i?.lazyContent?.detach()),i&&this._ownsMenu(i)&&_i.delete(i),this.restoreFocus&&(e==="keydown"||!this._openedBy||!this._triggersSubmenu())&&this.focus(this._openedBy),this._openedBy=void 0,this._setIsMenuOpen(false));}_setIsMenuOpen(e){e!==this._menuOpen&&(this._menuOpen=e,this._menuOpen?this.menuOpened.emit():this.menuClosed.emit(),this._triggersSubmenu()&&this._menuItemInstance._setHighlighted(e),this._changeDetectorRef.markForCheck());}_createOverlay(e){if(!this._overlayRef){let t=this._getOverlayConfig(e);this._subscribeToPositions(e,t.positionStrategy),this._overlayRef=Zt(this._injector,t),this._overlayRef.keydownEvents().subscribe(i=>{this._menu instanceof Fn&&this._menu._handleKeydown(i);});}return this._overlayRef}_getOverlayConfig(e){return new gt({positionStrategy:vi(this._injector,this._getOverlayOrigin()).withLockedPosition().withGrowAfterOpen().withTransformOriginOn(".mat-menu-panel, .mat-mdc-menu-panel"),backdropClass:e.backdropClass||"cdk-overlay-transparent-backdrop",panelClass:e.overlayPanelClass,scrollStrategy:this._scrollStrategy(),direction:this._dir||"ltr",disableAnimations:this._animationsDisabled})}_subscribeToPositions(e,t){e.setPositionClasses&&t.positionChanges.subscribe(i=>{this._ngZone.run(()=>{let a=i.connectionPair.overlayX==="start"?"after":"before",o=i.connectionPair.overlayY==="top"?"below":"above";e.setPositionClasses(a,o);});});}_setPosition(e,t){let[i,a]=e.xPosition==="before"?["end","start"]:["start","end"],[o,c]=e.yPosition==="above"?["bottom","top"]:["top","bottom"],[y,F]=[o,c],[H,De]=[i,a],_e=0;if(this._triggersSubmenu()){if(De=i=e.xPosition==="before"?"start":"end",a=H=i==="end"?"start":"end",this._parentMaterialMenu){if(this._parentInnerPadding==null){let Me=this._parentMaterialMenu.items.first;this._parentInnerPadding=Me?Me._getHostElement().offsetTop:0;}_e=o==="bottom"?this._parentInnerPadding:-this._parentInnerPadding;}}else e.overlapTrigger||(y=o==="top"?"bottom":"top",F=c==="top"?"bottom":"top");t.withPositions([{originX:i,originY:y,overlayX:H,overlayY:o,offsetY:_e},{originX:a,originY:y,overlayX:De,overlayY:o,offsetY:_e},{originX:i,originY:F,overlayX:H,overlayY:c,offsetY:-_e},{originX:a,originY:F,overlayX:De,overlayY:c,offsetY:-_e}]);}_menuClosingActions(){let e=this._getOutsideClickStream(this._overlayRef),t=this._overlayRef.detachments(),i=this._parentMaterialMenu?this._parentMaterialMenu.closed:rg(),a=this._parentMaterialMenu?this._parentMaterialMenu._hovered().pipe(en(o=>this._menuOpen&&o!==this._menuItemInstance)):rg();return kg(e,i,a,t)}_getPortal(e){return (!this._portal||this._portal.templateRef!==e.templateRef)&&(this._portal=new at$1(e.templateRef,this._viewContainerRef)),this._portal}_ownsMenu(e){return _i.get(e)===this}_triggerIsAriaDisabled(){return j0(this._element.nativeElement.getAttribute("aria-disabled"))}static \u0275fac=function(t){BI();};static \u0275dir=SE({type:n})}return n})(),fb=(()=>{class n extends Yc{_cleanupTouchstart;_hoverSubscription=j.EMPTY;get _deprecatedMatMenuTriggerFor(){return this.menu}set _deprecatedMatMenuTriggerFor(e){this.menu=e;}get menu(){return this._menu}set menu(e){this._menu=e;}menuData;restoreFocus=true;menuOpened=new $e;onMenuOpen=this.menuOpened;menuClosed=new $e;onMenuClose=this.menuClosed;constructor(){super(true);let e=T(MI);this._cleanupTouchstart=e.listen(this._element.nativeElement,"touchstart",t=>{Oe(t)||(this._openedBy="touch");},{passive:true});}triggersSubmenu(){return super._triggersSubmenu()}toggleMenu(){return this.menuOpen?this.closeMenu():this.openMenu()}openMenu(){this._openMenu(true);}closeMenu(){this._closeMenu();}updatePosition(){this._overlayRef?.updatePosition();}ngAfterContentInit(){this._handleHover();}ngOnDestroy(){super.ngOnDestroy(),this._cleanupTouchstart(),this._hoverSubscription.unsubscribe();}_getOverlayOrigin(){return this._element}_getOutsideClickStream(e){return e.backdropClick()}_handleMousedown(e){Re(e)||(this._openedBy=e.button===0?"mouse":void 0,this.triggersSubmenu()&&e.preventDefault());}_handleKeydown(e){let t=e.keyCode;(t===13||t===32)&&(this._openedBy="keyboard"),this.triggersSubmenu()&&(t===39&&this.dir==="ltr"||t===37&&this.dir==="rtl")&&(this._openedBy="keyboard",this.openMenu());}_handleClick(e){this.triggersSubmenu()?(e.stopPropagation(),this.openMenu()):this.toggleMenu();}_handleHover(){this.triggersSubmenu()&&this._parentMaterialMenu&&(this._hoverSubscription=this._parentMaterialMenu._hovered().subscribe(e=>{e===this._menuItemInstance&&!e.disabled&&this._parentMaterialMenu?._panelAnimationState!=="void"&&(this._openedBy="mouse",this._openMenu(false));}));}static \u0275fac=function(t){return new(t||n)};static \u0275dir=SE({type:n,selectors:[["","mat-menu-trigger-for",""],["","matMenuTriggerFor",""]],hostAttrs:[1,"mat-mdc-menu-trigger"],hostVars:3,hostBindings:function(t,i){t&1&&nh$1("click",function(o){return i._handleClick(o)})("mousedown",function(o){return i._handleMousedown(o)})("keydown",function(o){return i._handleKeydown(o)}),t&2&&Qp("aria-haspopup",i.menu?"menu":null)("aria-expanded",i.menuOpen)("aria-controls",i.menuOpen?i.menu?.panelId:null);},inputs:{_deprecatedMatMenuTriggerFor:[0,"mat-menu-trigger-for","_deprecatedMatMenuTriggerFor"],menu:[0,"matMenuTriggerFor","menu"],menuData:[0,"matMenuTriggerData","menuData"],restoreFocus:[0,"matMenuTriggerRestoreFocus","restoreFocus"]},outputs:{menuOpened:"menuOpened",onMenuOpen:"onMenuOpen",menuClosed:"menuClosed",onMenuClose:"onMenuClose"},exportAs:["matMenuTrigger"],features:[Hp$1]})}return n})();var za=(()=>{class n{static \u0275fac=function(t){return new(t||n)};static \u0275mod=bE({type:n});static \u0275inj=cu$1({imports:[Ye,Kt$1,jo$1,di]})}return n})();var $c=["trigger"],Xc=["panel"],Zc=[[["mat-select-trigger"]],"*"],Jc=["mat-select-trigger","*"];function em(n,r){if(n&1&&(yi(0,"span",4),UD(1),jc$1()),n&2){let e=pD();Wv(),Ih$1(e.placeholder);}}function tm(n,r){n&1&&mD(0);}function im(n,r){if(n&1&&(yi(0,"span",11),UD(1),jc$1()),n&2){let e=pD(2);Wv(),Ih$1(e.triggerValue);}}function nm(n,r){if(n&1&&(yi(0,"span",5),QE(1,tm,1,0)(2,im,2,1,"span",11),jc$1()),n&2){let e=pD();Wv(),ZE(e.customTrigger?1:2);}}function am(n,r){if(n&1){let e=iD();yi(0,"div",12,1),nh$1("keydown",function(i){ku(e);let a=pD();return Ou(a._handleKeydown(i))}),mD(2,1),jc$1();}if(n&2){let e=pD();RD(e.panelClass),fh$1("mat-select-panel-animations-enabled",!e._animationsDisabled)("mat-primary",e._parentFormField?.color==="primary")("mat-accent",e._parentFormField?.color==="accent")("mat-warn",e._parentFormField?.color==="warn")("mat-undefined",!e._parentFormField?.color),Qp("id",e.id+"-panel")("aria-multiselectable",e.multiple)("aria-label",e.ariaLabel||null)("aria-labelledby",e._getPanelAriaLabelledby());}}var rm=new x("mat-select-scroll-strategy",{providedIn:"root",factory:()=>{let n=T(ge);return ()=>gi$1(n)}}),om=new x("MAT_SELECT_CONFIG"),sm=new x("MatSelectTrigger"),Ha=class{source;value;constructor(r,e){this.source=r,this.value=e;}},Ks=(()=>{class n{_viewportRuler=T(he);_changeDetectorRef=T(L0);_elementRef=T(Dr$1);_dir=T(Er$2,{optional:true});_idGenerator=T(Y);_renderer=T(MI);_parentFormField=T(Wt,{optional:true});ngControl=T(lt,{self:true,optional:true});_liveAnnouncer=T(Jn);_defaultOptions=T(om,{optional:true});_animationsDisabled=B();_popoverLocation;_initialized=new ne;_cleanupDetach;options;optionGroups;customTrigger;_positions=[{originX:"start",originY:"bottom",overlayX:"start",overlayY:"top"},{originX:"end",originY:"bottom",overlayX:"end",overlayY:"top"},{originX:"start",originY:"top",overlayX:"start",overlayY:"bottom",panelClass:"mat-mdc-select-panel-above"},{originX:"end",originY:"top",overlayX:"end",overlayY:"bottom",panelClass:"mat-mdc-select-panel-above"}];_scrollOptionIntoView(e){let t=this.options.toArray()[e];if(t){let i=this.panel.nativeElement,a=Zm$1(e,this.options,this.optionGroups),o=t._getHostElement();e===0&&a===1?i.scrollTop=0:i.scrollTop=Km$1(o.offsetTop,o.offsetHeight,i.scrollTop,i.offsetHeight);}}_positioningSettled(){this._scrollOptionIntoView(this._keyManager.activeItemIndex||0);}_getChangeEvent(e){return new Ha(this,e)}_scrollStrategyFactory=T(rm);_panelOpen=false;_compareWith=(e,t)=>e===t;_uid=this._idGenerator.getId("mat-select-");_triggerAriaLabelledBy=null;_previousControl;_destroy=new ne;_errorStateTracker;stateChanges=new ne;disableAutomaticLabeling=true;userAriaDescribedBy;_selectionModel;_keyManager;_preferredOverlayOrigin;_overlayWidth;_onChange=()=>{};_onTouched=()=>{};_valueId=this._idGenerator.getId("mat-select-value-");_scrollStrategy;_overlayPanelClass=this._defaultOptions?.overlayPanelClass||"";get focused(){return this._focused||this._panelOpen}_focused=false;controlType="mat-select";trigger;panel;_overlayDir;panelClass;disabled=false;get disableRipple(){return this._disableRipple()}set disableRipple(e){this._disableRipple.set(e);}_disableRipple=Vo$1(false);tabIndex=0;get hideSingleSelectionIndicator(){return this._hideSingleSelectionIndicator}set hideSingleSelectionIndicator(e){this._hideSingleSelectionIndicator=e,this._syncParentProperties();}_hideSingleSelectionIndicator=this._defaultOptions?.hideSingleSelectionIndicator??false;get placeholder(){return this._placeholder}set placeholder(e){this._placeholder=e,this.stateChanges.next();}_placeholder;get required(){return this._required??this.ngControl?.control?.hasValidator(Gt.required)??false}set required(e){this._required=e,this.stateChanges.next();}_required;get multiple(){return this._multiple}set multiple(e){this._selectionModel,this._multiple=e;}_multiple=false;disableOptionCentering=this._defaultOptions?.disableOptionCentering??false;get compareWith(){return this._compareWith}set compareWith(e){this._compareWith=e,this._selectionModel&&this._initializeSelection();}get value(){return this._value}set value(e){this._assignValue(e)&&this._onChange(e);}_value;ariaLabel="";ariaLabelledby;get errorStateMatcher(){return this._errorStateTracker.matcher}set errorStateMatcher(e){this._errorStateTracker.matcher=e;}typeaheadDebounceInterval;sortComparator;get id(){return this._id}set id(e){this._id=e||this._uid,this.stateChanges.next();}_id;get errorState(){return this._errorStateTracker.errorState}set errorState(e){this._errorStateTracker.errorState=e;}panelWidth=this._defaultOptions&&typeof this._defaultOptions.panelWidth<"u"?this._defaultOptions.panelWidth:"auto";canSelectNullableOptions=this._defaultOptions?.canSelectNullableOptions??false;optionSelectionChanges=Cg(()=>{let e=this.options;return e?e.changes.pipe(Hl$1(e),Cl$1(()=>kg(...e.map(t=>t.onSelectionChange)))):this._initialized.pipe(Cl$1(()=>this.optionSelectionChanges))});openedChange=new $e;_openedStream=this.openedChange.pipe(en(e=>e),Ce(()=>{}));_closedStream=this.openedChange.pipe(en(e=>!e),Ce(()=>{}));selectionChange=new $e;valueChange=new $e;constructor(){let e=T(nf),t=T(Ti,{optional:true}),i=T(Ai,{optional:true}),a=T(new Ah$1("tabindex"),{optional:true}),o=T(yi$1,{optional:true});this.ngControl&&(this.ngControl.valueAccessor=this),this._defaultOptions?.typeaheadDebounceInterval!=null&&(this.typeaheadDebounceInterval=this._defaultOptions.typeaheadDebounceInterval),this._errorStateTracker=new Hr$1(e,this.ngControl,i,t,this.stateChanges),this._scrollStrategy=this._scrollStrategyFactory(),this.tabIndex=a==null?0:parseInt(a)||0,this._popoverLocation=o?.usePopover===false?null:"inline",this.id=this.id;}ngOnInit(){this._selectionModel=new Qt(this.multiple),this.stateChanges.next(),this._viewportRuler.change().pipe(Bl$1(this._destroy)).subscribe(()=>{this.panelOpen&&(this._overlayWidth=this._getOverlayWidth(this._preferredOverlayOrigin),this._changeDetectorRef.detectChanges());});}ngAfterContentInit(){this._initialized.next(),this._initialized.complete(),this._initKeyManager(),this._selectionModel.changed.pipe(Bl$1(this._destroy)).subscribe(e=>{e.added.forEach(t=>t.select()),e.removed.forEach(t=>t.deselect());}),this.options.changes.pipe(Hl$1(null),Bl$1(this._destroy)).subscribe(()=>{this._resetOptions(),this._initializeSelection();});}ngDoCheck(){let e=this._getTriggerAriaLabelledby(),t=this.ngControl;if(e!==this._triggerAriaLabelledBy){let i=this._elementRef.nativeElement;this._triggerAriaLabelledBy=e,e?i.setAttribute("aria-labelledby",e):i.removeAttribute("aria-labelledby");}t&&(this._previousControl!==t.control&&(this._previousControl!==void 0&&t.disabled!==null&&t.disabled!==this.disabled&&(this.disabled=t.disabled),this._previousControl=t.control),this.updateErrorState());}ngOnChanges(e){(e.disabled||e.userAriaDescribedBy)&&this.stateChanges.next(),e.typeaheadDebounceInterval&&this._keyManager&&this._keyManager.withTypeAhead(this.typeaheadDebounceInterval),e.panelClass&&this.panelClass instanceof Set&&(this.panelClass=Array.from(this.panelClass));}ngOnDestroy(){this._cleanupDetach?.(),this._keyManager?.destroy(),this._destroy.next(),this._destroy.complete(),this.stateChanges.complete();}toggle(){this.panelOpen?this.close():this.open();}open(){this._canOpen()&&(this._parentFormField&&(this._preferredOverlayOrigin=this._parentFormField.getConnectedOverlayOrigin()),this._cleanupDetach?.(),this._overlayWidth=this._getOverlayWidth(this._preferredOverlayOrigin),this._panelOpen=true,this._overlayDir.positionChange.pipe(tn(1)).subscribe(()=>{this._changeDetectorRef.detectChanges(),this._positioningSettled();}),this._overlayDir.attachOverlay(),this._keyManager.withHorizontalOrientation(null),this._highlightCorrectOption(),this._changeDetectorRef.markForCheck(),this.stateChanges.next(),Promise.resolve().then(()=>this.openedChange.emit(true)));}close(){this._panelOpen&&(this._panelOpen=false,this._exitAndDetach(),this._keyManager.withHorizontalOrientation(this._isRtl()?"rtl":"ltr"),this._changeDetectorRef.markForCheck(),this._onTouched(),this.stateChanges.next(),Promise.resolve().then(()=>this.openedChange.emit(false)));}_exitAndDetach(){if(this._animationsDisabled||!this.panel){this._detachOverlay();return}this._cleanupDetach?.(),this._cleanupDetach=()=>{t(),clearTimeout(i),this._cleanupDetach=void 0;};let e=this.panel.nativeElement,t=this._renderer.listen(e,"animationend",a=>{a.animationName==="_mat-select-exit"&&(this._cleanupDetach?.(),this._detachOverlay());}),i=setTimeout(()=>{this._cleanupDetach?.(),this._detachOverlay();},200);e.classList.add("mat-select-panel-exit");}_detachOverlay(){this._overlayDir.detachOverlay(),this._changeDetectorRef.markForCheck();}writeValue(e){this._assignValue(e);}registerOnChange(e){this._onChange=e;}registerOnTouched(e){this._onTouched=e;}setDisabledState(e){this.disabled=e,this._changeDetectorRef.markForCheck(),this.stateChanges.next();}get panelOpen(){return this._panelOpen}get selected(){return this.multiple?this._selectionModel?.selected||[]:this._selectionModel?.selected[0]}get triggerValue(){if(this.empty)return "";if(this._multiple){let e=this._selectionModel.selected.map(t=>t.viewValue);return this._isRtl()&&e.reverse(),e.join(", ")}return this._selectionModel.selected[0].viewValue}updateErrorState(){this._errorStateTracker.updateErrorState();}_isRtl(){return this._dir?this._dir.value==="rtl":false}_handleKeydown(e){this.disabled||(this.panelOpen?this._handleOpenKeydown(e):this._handleClosedKeydown(e));}_handleClosedKeydown(e){let t=e.keyCode,i=t===40||t===38||t===37||t===39,a=t===13||t===32,o=this._keyManager;if(!o.isTyping()&&a&&!$(e)||(this.multiple||e.altKey)&&i)e.preventDefault(),this.open();else if(!this.multiple){let c=this.selected;o.onKeydown(e);let y=this.selected;y&&c!==y&&this._liveAnnouncer.announce(y.viewValue,1e4);}}_handleOpenKeydown(e){let t=this._keyManager,i=e.keyCode,a=i===40||i===38,o=t.isTyping();if(a&&e.altKey)e.preventDefault(),this.close();else if(!o&&(i===13||i===32)&&t.activeItem&&!$(e))e.preventDefault(),t.activeItem._selectViaInteraction();else if(!o&&this._multiple&&i===65&&e.ctrlKey){e.preventDefault();let c=this.options.some(y=>!y.disabled&&!y.selected);this.options.forEach(y=>{y.disabled||(c?y.select():y.deselect());});}else {let c=t.activeItemIndex;t.onKeydown(e),this._multiple&&a&&e.shiftKey&&t.activeItem&&t.activeItemIndex!==c&&t.activeItem._selectViaInteraction();}}_handleOverlayKeydown(e){e.keyCode===27&&!$(e)&&(e.preventDefault(),this.close());}_onFocus(){this.disabled||(this._focused=true,this.stateChanges.next());}_onBlur(){this._focused=false,this._keyManager?.cancelTypeahead(),!this.disabled&&!this.panelOpen&&(this._onTouched(),this._changeDetectorRef.markForCheck(),this.stateChanges.next());}get empty(){return !this._selectionModel||this._selectionModel.isEmpty()}_initializeSelection(){Promise.resolve().then(()=>{this.ngControl&&(this._value=this.ngControl.value),this._setSelectionByValue(this._value),this.stateChanges.next();});}_setSelectionByValue(e){if(this.options.forEach(t=>t.setInactiveStyles()),this._selectionModel.clear(),this.multiple&&e)e.forEach(t=>this._selectOptionByValue(t)),this._sortValues();else {let t=this._selectOptionByValue(e);t?this._keyManager.updateActiveItem(t):this.panelOpen||this._keyManager.updateActiveItem(-1);}this._changeDetectorRef.markForCheck();}_selectOptionByValue(e){let t=this.options.find(i=>{if(this._selectionModel.isSelected(i))return  false;try{return (i.value!=null||this.canSelectNullableOptions)&&this._compareWith(i.value,e)}catch{return  false}});return t&&this._selectionModel.select(t),t}_assignValue(e){return e!==this._value||this._multiple&&Array.isArray(e)?(this.options&&this._setSelectionByValue(e),this._value=e,true):false}_skipPredicate=e=>this.panelOpen?false:e.disabled;_getOverlayWidth(e){return this.panelWidth==="auto"?(e instanceof bi$1?e.elementRef:e||this._elementRef).nativeElement.getBoundingClientRect().width:this.panelWidth===null?"":this.panelWidth}_syncParentProperties(){if(this.options)for(let e of this.options)e._changeDetectorRef.markForCheck();}_initKeyManager(){this._keyManager=new ei(this.options).withTypeAhead(this.typeaheadDebounceInterval).withVerticalOrientation().withHorizontalOrientation(this._isRtl()?"rtl":"ltr").withHomeAndEnd().withPageUpDown().withAllowedModifierKeys(["shiftKey"]).skipPredicate(this._skipPredicate),this._keyManager.tabOut.subscribe(()=>{this.panelOpen&&(!this.multiple&&this._keyManager.activeItem&&this._keyManager.activeItem._selectViaInteraction(),this.focus(),this.close());}),this._keyManager.change.subscribe(()=>{this._panelOpen&&this.panel?this._scrollOptionIntoView(this._keyManager.activeItemIndex||0):!this._panelOpen&&!this.multiple&&this._keyManager.activeItem&&this._keyManager.activeItem._selectViaInteraction();});}_resetOptions(){let e=kg(this.options.changes,this._destroy);this.optionSelectionChanges.pipe(Bl$1(e)).subscribe(t=>{this._onSelect(t.source,t.isUserInput),t.isUserInput&&!this.multiple&&this._panelOpen&&(this.close(),this.focus());}),kg(...this.options.map(t=>t._stateChanges)).pipe(Bl$1(e)).subscribe(()=>{this._changeDetectorRef.detectChanges(),this.stateChanges.next();});}_onSelect(e,t){let i=this._selectionModel.isSelected(e);!this.canSelectNullableOptions&&e.value==null&&!this._multiple?(e.deselect(),this._selectionModel.clear(),this.value!=null&&this._propagateChanges(e.value)):(i!==e.selected&&(e.selected?this._selectionModel.select(e):this._selectionModel.deselect(e)),t&&this._keyManager.setActiveItem(e),this.multiple&&(this._sortValues(),t&&this.focus())),i!==this._selectionModel.isSelected(e)&&this._propagateChanges(),this.stateChanges.next();}_sortValues(){if(this.multiple){let e=this.options.toArray();this._selectionModel.sort((t,i)=>this.sortComparator?this.sortComparator(t,i,e):e.indexOf(t)-e.indexOf(i)),this.stateChanges.next();}}_propagateChanges(e){let t;this.multiple?t=this.selected.map(i=>i.value):t=this.selected?this.selected.value:e,this._value=t,this.valueChange.emit(t),this._onChange(t),this.selectionChange.emit(this._getChangeEvent(t)),this._changeDetectorRef.markForCheck();}_highlightCorrectOption(){if(this._keyManager)if(this.empty){let e=-1;for(let t=0;t<this.options.length;t++)if(!this.options.get(t).disabled){e=t;break}this._keyManager.setActiveItem(e);}else this._keyManager.setActiveItem(this._selectionModel.selected[0]);}_canOpen(){return !this._panelOpen&&!this.disabled&&this.options?.length>0&&!!this._overlayDir}focus(e){this._elementRef.nativeElement.focus(e);}_getPanelAriaLabelledby(){if(this.ariaLabel)return null;let e=this._parentFormField?.getLabelId()||null,t=e?e+" ":"";return this.ariaLabelledby?t+this.ariaLabelledby:e}_getAriaActiveDescendant(){return this.panelOpen&&this._keyManager&&this._keyManager.activeItem?this._keyManager.activeItem.id:null}_getTriggerAriaLabelledby(){if(this.ariaLabel)return null;let e=this._parentFormField?.getLabelId()||"";return this.ariaLabelledby&&(e+=" "+this.ariaLabelledby),e||(e=this._valueId),e}get describedByIds(){return this._elementRef.nativeElement.getAttribute("aria-describedby")?.split(" ")||[]}setDescribedByIds(e){let t=this._elementRef.nativeElement;e.length?t.setAttribute("aria-describedby",e.join(" ")):t.removeAttribute("aria-describedby");}onContainerClick(e){let t=H(e);t&&(t.tagName==="MAT-OPTION"||t.classList.contains("cdk-overlay-backdrop")||t.closest(".mat-mdc-select-panel"))||(this.focus(),this.open());}get shouldLabelFloat(){return this.panelOpen||!this.empty||this.focused&&!!this.placeholder}static \u0275fac=function(t){return new(t||n)};static \u0275cmp=TE({type:n,selectors:[["mat-select"]],contentQueries:function(t,i,a){if(t&1&&ih$1(a,sm,5)(a,sr$1,5)(a,Qa$1,5),t&2){let o;vD(o=ID())&&(i.customTrigger=o.first),vD(o=ID())&&(i.options=o),vD(o=ID())&&(i.optionGroups=o);}},viewQuery:function(t,i){if(t&1&&sh$1($c,5)(Xc,5)(as$1,5),t&2){let a;vD(a=ID())&&(i.trigger=a.first),vD(a=ID())&&(i.panel=a.first),vD(a=ID())&&(i._overlayDir=a.first);}},hostAttrs:["role","combobox","aria-haspopup","listbox",1,"mat-mdc-select"],hostVars:21,hostBindings:function(t,i){t&1&&nh$1("keydown",function(o){return i._handleKeydown(o)})("focus",function(){return i._onFocus()})("blur",function(){return i._onBlur()}),t&2&&(Qp("id",i.id)("tabindex",i.disabled?-1:i.tabIndex)("aria-controls",i.panelOpen?i.id+"-panel":null)("aria-expanded",i.panelOpen)("aria-label",i.ariaLabel||null)("aria-required",i.required.toString())("aria-disabled",i.disabled.toString())("aria-invalid",i.errorState)("aria-activedescendant",i._getAriaActiveDescendant()),fh$1("mat-mdc-select-disabled",i.disabled)("mat-mdc-select-invalid",i.errorState)("mat-mdc-select-required",i.required)("mat-mdc-select-empty",i.empty)("mat-mdc-select-multiple",i.multiple)("mat-select-open",i.panelOpen));},inputs:{userAriaDescribedBy:[0,"aria-describedby","userAriaDescribedBy"],panelClass:"panelClass",disabled:[2,"disabled","disabled",j0],disableRipple:[2,"disableRipple","disableRipple",j0],tabIndex:[2,"tabIndex","tabIndex",e=>e==null?0:V0(e)],hideSingleSelectionIndicator:[2,"hideSingleSelectionIndicator","hideSingleSelectionIndicator",j0],placeholder:"placeholder",required:[2,"required","required",j0],multiple:[2,"multiple","multiple",j0],disableOptionCentering:[2,"disableOptionCentering","disableOptionCentering",j0],compareWith:"compareWith",value:"value",ariaLabel:[0,"aria-label","ariaLabel"],ariaLabelledby:[0,"aria-labelledby","ariaLabelledby"],errorStateMatcher:"errorStateMatcher",typeaheadDebounceInterval:[2,"typeaheadDebounceInterval","typeaheadDebounceInterval",V0],sortComparator:"sortComparator",id:"id",panelWidth:"panelWidth",canSelectNullableOptions:[2,"canSelectNullableOptions","canSelectNullableOptions",j0]},outputs:{openedChange:"openedChange",_openedStream:"opened",_closedStream:"closed",selectionChange:"selectionChange",valueChange:"valueChange"},exportAs:["matSelect"],features:[JD([{provide:Ri,useExisting:n},{provide:qa$1,useExisting:n}]),zm$1],ngContentSelectors:Jc,decls:11,vars:10,consts:[["fallbackOverlayOrigin","cdkOverlayOrigin","trigger",""],["panel",""],["cdk-overlay-origin","",1,"mat-mdc-select-trigger",3,"click"],[1,"mat-mdc-select-value"],[1,"mat-mdc-select-placeholder","mat-mdc-select-min-line"],[1,"mat-mdc-select-value-text"],[1,"mat-mdc-select-arrow-wrapper"],[1,"mat-mdc-select-arrow"],["viewBox","0 0 24 24","width","24px","height","24px","focusable","false","aria-hidden","true"],["d","M7 10l5 5 5-5z"],["cdk-connected-overlay","","cdkConnectedOverlayHasBackdrop","","cdkConnectedOverlayBackdropClass","cdk-overlay-transparent-backdrop",3,"detach","backdropClick","overlayKeydown","cdkConnectedOverlayDisableClose","cdkConnectedOverlayPanelClass","cdkConnectedOverlayScrollStrategy","cdkConnectedOverlayOrigin","cdkConnectedOverlayPositions","cdkConnectedOverlayWidth","cdkConnectedOverlayFlexibleDimensions","cdkConnectedOverlayUsePopover"],[1,"mat-mdc-select-min-line"],["role","listbox","tabindex","-1",1,"mat-mdc-select-panel","mdc-menu-surface","mdc-menu-surface--open",3,"keydown"]],template:function(t,i){if(t&1&&(gD(Zc),yi(0,"div",2,0),nh$1("click",function(){return i.open()}),yi(3,"div",3),QE(4,em,2,1,"span",4)(5,nm,3,1,"span",5),jc$1(),yi(6,"div",6)(7,"div",7),Gu(),yi(8,"svg",8),Yp(9,"path",9),jc$1()()()(),$p(10,am,3,16,"ng-template",10),nh$1("detach",function(){return i.close()})("backdropClick",function(){return i.close()})("overlayKeydown",function(o){return i._handleOverlayKeydown(o)})),t&2){let a=DD(1);Wv(3),Qp("id",i._valueId),Wv(),ZE(i.empty?4:5),Wv(6),Zp("cdkConnectedOverlayDisableClose",true)("cdkConnectedOverlayPanelClass",i._overlayPanelClass)("cdkConnectedOverlayScrollStrategy",i._scrollStrategy)("cdkConnectedOverlayOrigin",i._preferredOverlayOrigin||a)("cdkConnectedOverlayPositions",i._positions)("cdkConnectedOverlayWidth",i._overlayWidth)("cdkConnectedOverlayFlexibleDimensions",true)("cdkConnectedOverlayUsePopover",i._popoverLocation);}},dependencies:[bi$1,as$1],styles:[`@keyframes _mat-select-enter {
  from {
    opacity: 0;
    transform: scaleY(0.8);
  }
  to {
    opacity: 1;
    transform: none;
  }
}
@keyframes _mat-select-exit {
  from {
    opacity: 1;
  }
  to {
    opacity: 0;
  }
}
.mat-mdc-select {
  display: inline-block;
  width: 100%;
  outline: none;
  -moz-osx-font-smoothing: grayscale;
  -webkit-font-smoothing: antialiased;
  color: var(--mat-select-enabled-trigger-text-color, var(--mat-sys-on-surface));
  font-family: var(--mat-select-trigger-text-font, var(--mat-sys-body-large-font));
  line-height: var(--mat-select-trigger-text-line-height, var(--mat-sys-body-large-line-height));
  font-size: var(--mat-select-trigger-text-size, var(--mat-sys-body-large-size));
  font-weight: var(--mat-select-trigger-text-weight, var(--mat-sys-body-large-weight));
  letter-spacing: var(--mat-select-trigger-text-tracking, var(--mat-sys-body-large-tracking));
}

div.mat-mdc-select-panel {
  box-shadow: var(--mat-select-container-elevation-shadow, 0px 3px 1px -2px rgba(0, 0, 0, 0.2), 0px 2px 2px 0px rgba(0, 0, 0, 0.14), 0px 1px 5px 0px rgba(0, 0, 0, 0.12));
}

.mat-mdc-select-disabled {
  color: var(--mat-select-disabled-trigger-text-color, color-mix(in srgb, var(--mat-sys-on-surface) 38%, transparent));
}
.mat-mdc-select-disabled .mat-mdc-select-placeholder {
  color: var(--mat-select-disabled-trigger-text-color, color-mix(in srgb, var(--mat-sys-on-surface) 38%, transparent));
}

.mat-mdc-select-trigger {
  display: inline-flex;
  align-items: center;
  cursor: pointer;
  position: relative;
  box-sizing: border-box;
  width: 100%;
}
.mat-mdc-select-disabled .mat-mdc-select-trigger {
  -webkit-user-select: none;
  user-select: none;
  cursor: default;
}

.mat-mdc-select-value {
  width: 100%;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.mat-mdc-select-value-text {
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.mat-mdc-select-arrow-wrapper {
  height: 24px;
  flex-shrink: 0;
  display: inline-flex;
  align-items: center;
}
.mat-form-field-appearance-fill .mdc-text-field--no-label .mat-mdc-select-arrow-wrapper {
  transform: none;
}

.mat-mdc-form-field .mat-mdc-select.mat-mdc-select-invalid .mat-mdc-select-arrow,
.mat-form-field-invalid:not(.mat-form-field-disabled) .mat-mdc-form-field-infix::after {
  color: var(--mat-select-invalid-arrow-color, var(--mat-sys-error));
}

.mat-mdc-select-arrow {
  width: 10px;
  height: 5px;
  position: relative;
  color: var(--mat-select-enabled-arrow-color, var(--mat-sys-on-surface-variant));
}
.mat-mdc-form-field.mat-focused .mat-mdc-select-arrow {
  color: var(--mat-select-focused-arrow-color, var(--mat-sys-primary));
}
.mat-mdc-form-field .mat-mdc-select.mat-mdc-select-disabled .mat-mdc-select-arrow {
  color: var(--mat-select-disabled-arrow-color, color-mix(in srgb, var(--mat-sys-on-surface) 38%, transparent));
}
.mat-select-open .mat-mdc-select-arrow {
  transform: rotate(180deg);
}
.mat-form-field-animations-enabled .mat-mdc-select-arrow {
  transition: transform 80ms linear;
}
.mat-mdc-select-arrow svg {
  fill: currentColor;
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
}
@media (forced-colors: active) {
  .mat-mdc-select-arrow svg {
    fill: CanvasText;
  }
  .mat-mdc-select-disabled .mat-mdc-select-arrow svg {
    fill: GrayText;
  }
}

div.mat-mdc-select-panel {
  width: 100%;
  max-height: 275px;
  outline: 0;
  overflow: auto;
  padding: 8px 0;
  box-sizing: border-box;
  transform-origin: top center;
  border-radius: 0 0 4px 4px;
  position: relative;
  background-color: var(--mat-select-panel-background-color, var(--mat-sys-surface-container));
}
.mat-mdc-select-panel-above div.mat-mdc-select-panel {
  border-radius: 4px 4px 0 0;
  transform-origin: bottom center;
}
@media (forced-colors: active) {
  div.mat-mdc-select-panel {
    outline: solid 1px;
  }
}

.mat-select-panel-animations-enabled {
  animation: _mat-select-enter 120ms cubic-bezier(0, 0, 0.2, 1);
}
.mat-select-panel-animations-enabled.mat-select-panel-exit {
  animation: _mat-select-exit 100ms linear;
}

.mat-mdc-select-placeholder {
  transition: color 400ms 133.3333333333ms cubic-bezier(0.25, 0.8, 0.25, 1);
  color: var(--mat-select-placeholder-text-color, var(--mat-sys-on-surface-variant));
}
.mat-mdc-form-field:not(.mat-form-field-animations-enabled) .mat-mdc-select-placeholder, ._mat-animation-noopable .mat-mdc-select-placeholder {
  transition: none;
}
.mat-form-field-hide-placeholder .mat-mdc-select-placeholder {
  color: transparent;
  -webkit-text-fill-color: transparent;
  transition: none;
  display: block;
}

.mat-mdc-form-field-type-mat-select:not(.mat-form-field-disabled) .mat-mdc-text-field-wrapper {
  cursor: pointer;
}
.mat-mdc-form-field-type-mat-select.mat-form-field-appearance-fill .mat-mdc-floating-label {
  max-width: calc(100% - 18px);
}
.mat-mdc-form-field-type-mat-select.mat-form-field-appearance-fill .mdc-floating-label--float-above {
  max-width: calc(100% / 0.75 - 24px);
}
.mat-mdc-form-field-type-mat-select.mat-form-field-appearance-outline .mdc-notched-outline__notch {
  max-width: calc(100% - 60px);
}
.mat-mdc-form-field-type-mat-select.mat-form-field-appearance-outline .mdc-text-field--label-floating .mdc-notched-outline__notch {
  max-width: calc(100% - 24px);
}

.mat-mdc-select-min-line:empty::before {
  content: " ";
  white-space: pre;
  width: 1px;
  display: inline-block;
  visibility: hidden;
}

.mat-form-field-appearance-fill .mat-mdc-select-arrow-wrapper {
  transform: var(--mat-select-arrow-transform, translateY(-8px));
}
`],encapsulation:2})}return n})();var Vi=(()=>{class n{static \u0275fac=function(t){return new(t||n)};static \u0275mod=bE({type:n});static \u0275inj=cu$1({imports:[Kt$1,js$1,jo$1,di,Ot,js$1]})}return n})();var Li=(()=>{class n{static \u0275fac=function(t){return new(t||n)};static \u0275mod=bE({type:n});static \u0275inj=cu$1({imports:[ti,Kt$1,jo$1,di]})}return n})();function lm(n,r){if(n&1&&(yi(0,"mat-option",17),UD(1),jc$1()),n&2){let e=r.$implicit;Zp("value",e),Wv(),Uc$1(" ",e," ");}}function dm(n,r){if(n&1){let e=iD();yi(0,"mat-form-field",14)(1,"mat-select",16,0),nh$1("selectionChange",function(i){ku(e);let a=pD(2);return Ou(a._changePageSize(i.value))}),JE(3,lm,2,2,"mat-option",17,KE),jc$1(),yi(5,"div",18),nh$1("click",function(){ku(e);let i=DD(2);return Ou(i.open())}),jc$1()();}if(n&2){let e=pD(2);Zp("appearance",e._formFieldAppearance)("color",e.color),Wv(),Zp("value",e.pageSize)("disabled",e.disabled),zp$1("aria-labelledby",e._pageSizeLabelId),Zp("panelClass",e.selectConfig.panelClass||"")("disableOptionCentering",e.selectConfig.disableOptionCentering),Wv(2),XE(e._displayedPageSizeOptions);}}function cm(n,r){if(n&1&&(yi(0,"div",15),UD(1),jc$1()),n&2){let e=pD(2);Wv(),Ih$1(e.pageSize);}}function mm(n,r){if(n&1&&(yi(0,"div",3)(1,"div",13),UD(2),jc$1(),QE(3,dm,6,7,"mat-form-field",14),QE(4,cm,2,1,"div",15),jc$1()),n&2){let e=pD();Wv(),Qp("id",e._pageSizeLabelId),Wv(),Uc$1(" ",e._intl.itemsPerPageLabel," "),Wv(),ZE(e._displayedPageSizeOptions.length>1?3:-1),Wv(),ZE(e._displayedPageSizeOptions.length<=1?4:-1);}}function hm(n,r){if(n&1){let e=iD();yi(0,"button",19),nh$1("click",function(){ku(e);let i=pD();return Ou(i._buttonClicked(0,i._previousButtonsDisabled()))}),Gu(),yi(1,"svg",8),Yp(2,"path",20),jc$1()();}if(n&2){let e=pD();Zp("matTooltip",e._intl.firstPageLabel)("matTooltipDisabled",e._previousButtonsDisabled())("disabled",e._previousButtonsDisabled())("tabindex",e._previousButtonsDisabled()?-1:null),Qp("aria-label",e._intl.firstPageLabel);}}function pm(n,r){if(n&1){let e=iD();yi(0,"button",21),nh$1("click",function(){ku(e);let i=pD();return Ou(i._buttonClicked(i.getNumberOfPages()-1,i._nextButtonsDisabled()))}),Gu(),yi(1,"svg",8),Yp(2,"path",22),jc$1()();}if(n&2){let e=pD();Zp("matTooltip",e._intl.lastPageLabel)("matTooltipDisabled",e._nextButtonsDisabled())("disabled",e._nextButtonsDisabled())("tabindex",e._nextButtonsDisabled()?-1:null),Qp("aria-label",e._intl.lastPageLabel);}}var um=(()=>{class n{changes=new ne;itemsPerPageLabel="Items per page:";nextPageLabel="Next page";previousPageLabel="Previous page";firstPageLabel="First page";lastPageLabel="Last page";getRangeLabel=(e,t,i)=>{if(i==0||t==0)return `0 of ${i}`;i=Math.max(i,0);let a=e*t,o=a<i?Math.min(a+t,i):a+t;return `${a+1} \u2013 ${o} of ${i}`};static \u0275fac=function(t){return new(t||n)};static \u0275prov=Er$1({token:n,factory:n.\u0275fac})}return n})(),_m=50;var fm=new x("MAT_PAGINATOR_DEFAULT_OPTIONS"),gm=(()=>{class n{_intl=T(um);_changeDetectorRef=T(L0);_formFieldAppearance;_pageSizeLabelId=T(Y).getId("mat-paginator-page-size-label-");_intlChanges;_isInitialized=false;_initializedStream=new On$1(1);color;get pageIndex(){return this._pageIndex}set pageIndex(e){this._pageIndex=Math.max(e||0,0),this._changeDetectorRef.markForCheck();}_pageIndex=0;get length(){return this._length}set length(e){this._length=e||0,this._changeDetectorRef.markForCheck();}_length=0;get pageSize(){return this._pageSize}set pageSize(e){this._pageSize=Math.max(e||0,0),this._updateDisplayedPageSizeOptions();}_pageSize;get pageSizeOptions(){return this._pageSizeOptions}set pageSizeOptions(e){this._pageSizeOptions=(e||[]).map(t=>V0(t,0)),this._updateDisplayedPageSizeOptions();}_pageSizeOptions=[];hidePageSize=false;showFirstLastButtons=false;selectConfig={};disabled=false;page=new $e;_displayedPageSizeOptions;initialized=this._initializedStream;constructor(){let e=this._intl,t=T(fm,{optional:true});if(this._intlChanges=e.changes.subscribe(()=>this._changeDetectorRef.markForCheck()),t){let{pageSize:i,pageSizeOptions:a,hidePageSize:o,showFirstLastButtons:c}=t;i!=null&&(this._pageSize=i),a!=null&&(this._pageSizeOptions=a),o!=null&&(this.hidePageSize=o),c!=null&&(this.showFirstLastButtons=c);}this._formFieldAppearance=t?.formFieldAppearance||"outline";}ngOnInit(){this._isInitialized=true,this._updateDisplayedPageSizeOptions(),this._initializedStream.next();}ngOnDestroy(){this._initializedStream.complete(),this._intlChanges.unsubscribe();}nextPage(){this.hasNextPage()&&this._navigate(this.pageIndex+1);}previousPage(){this.hasPreviousPage()&&this._navigate(this.pageIndex-1);}firstPage(){this.hasPreviousPage()&&this._navigate(0);}lastPage(){this.hasNextPage()&&this._navigate(this.getNumberOfPages()-1);}hasPreviousPage(){return this.pageIndex>=1&&this.pageSize!=0}hasNextPage(){let e=this.getNumberOfPages()-1;return this.pageIndex<e&&this.pageSize!=0}getNumberOfPages(){return this.pageSize?Math.ceil(this.length/this.pageSize):0}_changePageSize(e){let t=this.pageIndex*this.pageSize,i=this.pageIndex;this.pageIndex=Math.floor(t/e)||0,this.pageSize=e,this._emitPageEvent(i);}_nextButtonsDisabled(){return this.disabled||!this.hasNextPage()}_previousButtonsDisabled(){return this.disabled||!this.hasPreviousPage()}_updateDisplayedPageSizeOptions(){this._isInitialized&&(this.pageSize||(this._pageSize=this.pageSizeOptions.length!=0?this.pageSizeOptions[0]:_m),this._displayedPageSizeOptions=this.pageSizeOptions.slice(),this._displayedPageSizeOptions.indexOf(this.pageSize)===-1&&this._displayedPageSizeOptions.push(this.pageSize),this._displayedPageSizeOptions.sort((e,t)=>e-t),this._changeDetectorRef.markForCheck());}_emitPageEvent(e){this.page.emit({previousPageIndex:e,pageIndex:this.pageIndex,pageSize:this.pageSize,length:this.length});}_navigate(e){let t=this.pageIndex;e!==t&&(this.pageIndex=e,this._emitPageEvent(t));}_buttonClicked(e,t){t||this._navigate(e);}static \u0275fac=function(t){return new(t||n)};static \u0275cmp=TE({type:n,selectors:[["mat-paginator"]],hostAttrs:["role","group",1,"mat-mdc-paginator"],inputs:{color:"color",pageIndex:[2,"pageIndex","pageIndex",V0],length:[2,"length","length",V0],pageSize:[2,"pageSize","pageSize",V0],pageSizeOptions:"pageSizeOptions",hidePageSize:[2,"hidePageSize","hidePageSize",j0],showFirstLastButtons:[2,"showFirstLastButtons","showFirstLastButtons",j0],selectConfig:"selectConfig",disabled:[2,"disabled","disabled",j0]},outputs:{page:"page"},exportAs:["matPaginator"],decls:14,vars:14,consts:[["selectRef",""],[1,"mat-mdc-paginator-outer-container"],[1,"mat-mdc-paginator-container"],[1,"mat-mdc-paginator-page-size"],[1,"mat-mdc-paginator-range-actions"],["aria-atomic","true","aria-live","polite","role","status",1,"mat-mdc-paginator-range-label"],["matIconButton","","type","button","matTooltipPosition","above","disabledInteractive","",1,"mat-mdc-paginator-navigation-first",3,"matTooltip","matTooltipDisabled","disabled","tabindex"],["matIconButton","","type","button","matTooltipPosition","above","disabledInteractive","",1,"mat-mdc-paginator-navigation-previous",3,"click","matTooltip","matTooltipDisabled","disabled","tabindex"],["viewBox","0 0 24 24","focusable","false","aria-hidden","true",1,"mat-mdc-paginator-icon"],["d","M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"],["matIconButton","","type","button","matTooltipPosition","above","disabledInteractive","",1,"mat-mdc-paginator-navigation-next",3,"click","matTooltip","matTooltipDisabled","disabled","tabindex"],["d","M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"],["matIconButton","","type","button","matTooltipPosition","above","disabledInteractive","",1,"mat-mdc-paginator-navigation-last",3,"matTooltip","matTooltipDisabled","disabled","tabindex"],["aria-hidden","true",1,"mat-mdc-paginator-page-size-label"],[1,"mat-mdc-paginator-page-size-select",3,"appearance","color"],[1,"mat-mdc-paginator-page-size-value"],["hideSingleSelectionIndicator","",3,"selectionChange","value","disabled","aria-labelledby","panelClass","disableOptionCentering"],[3,"value"],[1,"mat-mdc-paginator-touch-target",3,"click"],["matIconButton","","type","button","matTooltipPosition","above","disabledInteractive","",1,"mat-mdc-paginator-navigation-first",3,"click","matTooltip","matTooltipDisabled","disabled","tabindex"],["d","M18.41 16.59L13.82 12l4.59-4.59L17 6l-6 6 6 6zM6 6h2v12H6z"],["matIconButton","","type","button","matTooltipPosition","above","disabledInteractive","",1,"mat-mdc-paginator-navigation-last",3,"click","matTooltip","matTooltipDisabled","disabled","tabindex"],["d","M5.59 7.41L10.18 12l-4.59 4.59L7 18l6-6-6-6zM16 6h2v12h-2z"]],template:function(t,i){t&1&&(yi(0,"div",1)(1,"div",2),QE(2,mm,5,4,"div",3),yi(3,"div",4)(4,"div",5),UD(5),jc$1(),QE(6,hm,3,5,"button",6),yi(7,"button",7),nh$1("click",function(){return i._buttonClicked(i.pageIndex-1,i._previousButtonsDisabled())}),Gu(),yi(8,"svg",8),Yp(9,"path",9),jc$1()(),zu(),yi(10,"button",10),nh$1("click",function(){return i._buttonClicked(i.pageIndex+1,i._nextButtonsDisabled())}),Gu(),yi(11,"svg",8),Yp(12,"path",11),jc$1()(),QE(13,pm,3,5,"button",12),jc$1()()()),t&2&&(Wv(2),ZE(i.hidePageSize?-1:2),Wv(3),Uc$1(" ",i._intl.getRangeLabel(i.pageIndex,i.pageSize,i.length)," "),Wv(),ZE(i.showFirstLastButtons?6:-1),Wv(),Zp("matTooltip",i._intl.previousPageLabel)("matTooltipDisabled",i._previousButtonsDisabled())("disabled",i._previousButtonsDisabled())("tabindex",i._previousButtonsDisabled()?-1:null),Qp("aria-label",i._intl.previousPageLabel),Wv(3),Zp("matTooltip",i._intl.nextPageLabel)("matTooltipDisabled",i._nextButtonsDisabled())("disabled",i._nextButtonsDisabled())("tabindex",i._nextButtonsDisabled()?-1:null),Qp("aria-label",i._intl.nextPageLabel),Wv(3),ZE(i.showFirstLastButtons?13:-1));},dependencies:[En,Ks,sr$1,Ss$1,Fi],styles:[`.mat-mdc-paginator {
  display: block;
  -moz-osx-font-smoothing: grayscale;
  -webkit-font-smoothing: antialiased;
  color: var(--mat-paginator-container-text-color, var(--mat-sys-on-surface));
  background-color: var(--mat-paginator-container-background-color, var(--mat-sys-surface));
  font-family: var(--mat-paginator-container-text-font, var(--mat-sys-body-small-font));
  line-height: var(--mat-paginator-container-text-line-height, var(--mat-sys-body-small-line-height));
  font-size: var(--mat-paginator-container-text-size, var(--mat-sys-body-small-size));
  font-weight: var(--mat-paginator-container-text-weight, var(--mat-sys-body-small-weight));
  letter-spacing: var(--mat-paginator-container-text-tracking, var(--mat-sys-body-small-tracking));
  --mat-form-field-container-height: var(--mat-paginator-form-field-container-height, 40px);
  --mat-form-field-container-vertical-padding: var(--mat-paginator-form-field-container-vertical-padding, 8px);
}
.mat-mdc-paginator .mat-mdc-select-value {
  font-size: var(--mat-paginator-select-trigger-text-size, var(--mat-sys-body-small-size));
}
.mat-mdc-paginator .mat-mdc-form-field-subscript-wrapper {
  display: none;
}
.mat-mdc-paginator .mat-mdc-select {
  line-height: 1.5;
}

.mat-mdc-paginator-outer-container {
  display: flex;
}

.mat-mdc-paginator-container {
  display: flex;
  align-items: center;
  justify-content: flex-end;
  padding: 0 8px;
  flex-wrap: wrap;
  width: 100%;
  min-height: var(--mat-paginator-container-size, 56px);
}

.mat-mdc-paginator-page-size {
  display: flex;
  align-items: baseline;
  margin-right: 8px;
}
[dir=rtl] .mat-mdc-paginator-page-size {
  margin-right: 0;
  margin-left: 8px;
}

.mat-mdc-paginator-page-size-label {
  margin: 0 4px;
}

.mat-mdc-paginator-page-size-select {
  margin: 0 4px;
  width: var(--mat-paginator-page-size-select-width, 84px);
}

.mat-mdc-paginator-range-label {
  margin: 0 32px 0 24px;
}

.mat-mdc-paginator-range-actions {
  display: flex;
  align-items: center;
}

.mat-mdc-paginator-icon {
  display: inline-block;
  width: 28px;
  fill: var(--mat-paginator-enabled-icon-color, var(--mat-sys-on-surface-variant));
}
.mat-mdc-icon-button[aria-disabled] .mat-mdc-paginator-icon {
  fill: var(--mat-paginator-disabled-icon-color, color-mix(in srgb, var(--mat-sys-on-surface) 38%, transparent));
}
[dir=rtl] .mat-mdc-paginator-icon {
  transform: rotate(180deg);
}

@media (forced-colors: active) {
  .mat-mdc-icon-button[aria-disabled] .mat-mdc-paginator-icon,
  .mat-mdc-paginator-icon {
    fill: currentColor;
  }
  .mat-mdc-paginator-range-actions .mat-mdc-icon-button {
    outline: solid 1px;
  }
  .mat-mdc-paginator-range-actions .mat-mdc-icon-button[aria-disabled] {
    color: GrayText;
  }
}
.mat-mdc-paginator-touch-target {
  display: var(--mat-paginator-touch-target-display, block);
  position: absolute;
  top: 50%;
  left: 50%;
  width: var(--mat-paginator-page-size-select-width, 84px);
  height: var(--mat-paginator-page-size-select-touch-target-height, 48px);
  background-color: transparent;
  transform: translate(-50%, -50%);
  cursor: pointer;
}
`],encapsulation:2})}return n})(),ja=(()=>{class n{static \u0275fac=function(t){return new(t||n)};static \u0275mod=bE({type:n});static \u0275inj=cu$1({imports:[Br$1,Vi,Li,gm]})}return n})();function bm(n,r){n&1&&Kp(0,"div",2);}var vm=new x("MAT_PROGRESS_BAR_DEFAULT_OPTIONS");var Cv=(()=>{class n{_elementRef=T(Dr$1);_ngZone=T(Se);_changeDetectorRef=T(L0);_renderer=T(MI);_cleanupTransitionEnd;constructor(){let e=Ba$1(),t=T(vm,{optional:true});this._isNoopAnimation=e==="di-disabled",e==="reduced-motion"&&this._elementRef.nativeElement.classList.add("mat-progress-bar-reduced-motion"),t&&(t.color&&(this.color=this._defaultColor=t.color),this.mode=t.mode||this.mode);}_isNoopAnimation;get color(){return this._color||this._defaultColor}set color(e){this._color=e;}_color;_defaultColor="primary";get value(){return this._value}set value(e){this._value=$s(e||0),this._changeDetectorRef.markForCheck();}_value=0;get bufferValue(){return this._bufferValue||0}set bufferValue(e){this._bufferValue=$s(e||0),this._changeDetectorRef.markForCheck();}_bufferValue=0;animationEnd=new $e;get mode(){return this._mode}set mode(e){this._mode=e,this._changeDetectorRef.markForCheck();}_mode="determinate";ngAfterViewInit(){this._ngZone.runOutsideAngular(()=>{this._cleanupTransitionEnd=this._renderer.listen(this._elementRef.nativeElement,"transitionend",this._transitionendHandler);});}ngOnDestroy(){this._cleanupTransitionEnd?.();}_getPrimaryBarTransform(){return `scaleX(${this._isIndeterminate()?1:this.value/100})`}_getBufferBarFlexBasis(){return `${this.mode==="buffer"?this.bufferValue:100}%`}_isIndeterminate(){return this.mode==="indeterminate"||this.mode==="query"}_transitionendHandler=e=>{this.animationEnd.observers.length===0||!e.target||!e.target.classList.contains("mdc-linear-progress__primary-bar")||(this.mode==="determinate"||this.mode==="buffer")&&this._ngZone.run(()=>this.animationEnd.next({value:this.value}));};static \u0275fac=function(t){return new(t||n)};static \u0275cmp=TE({type:n,selectors:[["mat-progress-bar"]],hostAttrs:["role","progressbar","aria-valuemin","0","aria-valuemax","100","tabindex","-1",1,"mat-mdc-progress-bar","mdc-linear-progress"],hostVars:10,hostBindings:function(t,i){t&2&&(Qp("aria-valuenow",i._isIndeterminate()?null:i.value)("mode",i.mode),RD("mat-"+i.color),fh$1("_mat-animation-noopable",i._isNoopAnimation)("mdc-linear-progress--animation-ready",!i._isNoopAnimation)("mdc-linear-progress--indeterminate",i._isIndeterminate()));},inputs:{color:"color",value:[2,"value","value",V0],bufferValue:[2,"bufferValue","bufferValue",V0],mode:"mode"},outputs:{animationEnd:"animationEnd"},exportAs:["matProgressBar"],decls:7,vars:5,consts:[["aria-hidden","true",1,"mdc-linear-progress__buffer"],[1,"mdc-linear-progress__buffer-bar"],[1,"mdc-linear-progress__buffer-dots"],["aria-hidden","true",1,"mdc-linear-progress__bar","mdc-linear-progress__primary-bar"],[1,"mdc-linear-progress__bar-inner"],["aria-hidden","true",1,"mdc-linear-progress__bar","mdc-linear-progress__secondary-bar"]],template:function(t,i){t&1&&(Vc$1(0,"div",0),Kp(1,"div",1),QE(2,bm,1,0,"div",2),Hc$1(),Vc$1(3,"div",3),Kp(4,"span",4),Hc$1(),Vc$1(5,"div",5),Kp(6,"span",4),Hc$1()),t&2&&(Wv(),dh$1("flex-basis",i._getBufferBarFlexBasis()),Wv(),ZE(i.mode==="buffer"?2:-1),Wv(),dh$1("transform",i._getPrimaryBarTransform()));},styles:[`.mat-mdc-progress-bar {
  --mat-progress-bar-animation-multiplier: 1;
  display: block;
  text-align: start;
}
.mat-mdc-progress-bar[mode=query] {
  transform: scaleX(-1);
}
.mat-mdc-progress-bar._mat-animation-noopable .mdc-linear-progress__buffer-dots,
.mat-mdc-progress-bar._mat-animation-noopable .mdc-linear-progress__primary-bar,
.mat-mdc-progress-bar._mat-animation-noopable .mdc-linear-progress__secondary-bar,
.mat-mdc-progress-bar._mat-animation-noopable .mdc-linear-progress__bar-inner.mdc-linear-progress__bar-inner {
  animation: none;
}
.mat-mdc-progress-bar._mat-animation-noopable .mdc-linear-progress__primary-bar,
.mat-mdc-progress-bar._mat-animation-noopable .mdc-linear-progress__buffer-bar {
  transition: transform 1ms;
}

.mat-progress-bar-reduced-motion {
  --mat-progress-bar-animation-multiplier: 2;
}

.mdc-linear-progress {
  position: relative;
  width: 100%;
  transform: translateZ(0);
  outline: 1px solid transparent;
  overflow-x: hidden;
  transition: opacity 250ms 0ms cubic-bezier(0.4, 0, 0.6, 1);
  height: max(var(--mat-progress-bar-track-height, 4px), var(--mat-progress-bar-active-indicator-height, 4px));
}
@media (forced-colors: active) {
  .mdc-linear-progress {
    outline-color: CanvasText;
  }
}

.mdc-linear-progress__bar {
  position: absolute;
  top: 0;
  bottom: 0;
  margin: auto 0;
  width: 100%;
  animation: none;
  transform-origin: top left;
  transition: transform 250ms 0ms cubic-bezier(0.4, 0, 0.6, 1);
  height: var(--mat-progress-bar-active-indicator-height, 4px);
}
.mdc-linear-progress--indeterminate .mdc-linear-progress__bar {
  transition: none;
}
[dir=rtl] .mdc-linear-progress__bar {
  right: 0;
  transform-origin: center right;
}

.mdc-linear-progress__bar-inner {
  display: inline-block;
  position: absolute;
  width: 100%;
  animation: none;
  border-top-style: solid;
  border-color: var(--mat-progress-bar-active-indicator-color, var(--mat-sys-primary));
  border-top-width: var(--mat-progress-bar-active-indicator-height, 4px);
}

.mdc-linear-progress__buffer {
  display: flex;
  position: absolute;
  top: 0;
  bottom: 0;
  margin: auto 0;
  width: 100%;
  overflow: hidden;
  height: var(--mat-progress-bar-track-height, 4px);
  border-radius: var(--mat-progress-bar-track-shape, var(--mat-sys-corner-none));
}

.mdc-linear-progress__buffer-dots {
  background-image: radial-gradient(circle, var(--mat-progress-bar-track-color, var(--mat-sys-surface-variant)) calc(var(--mat-progress-bar-track-height, 4px) / 2), transparent 0);
  background-repeat: repeat-x;
  background-size: calc(calc(var(--mat-progress-bar-track-height, 4px) / 2) * 5);
  background-position: left;
  flex: auto;
  transform: rotate(180deg);
  animation: mdc-linear-progress-buffering calc(250ms * var(--mat-progress-bar-animation-multiplier)) infinite linear;
}
@media (forced-colors: active) {
  .mdc-linear-progress__buffer-dots {
    background-color: ButtonBorder;
  }
}
[dir=rtl] .mdc-linear-progress__buffer-dots {
  animation: mdc-linear-progress-buffering-reverse calc(250ms * var(--mat-progress-bar-animation-multiplier)) infinite linear;
  transform: rotate(0);
}

.mdc-linear-progress__buffer-bar {
  flex: 0 1 100%;
  transition: flex-basis 250ms 0ms cubic-bezier(0.4, 0, 0.6, 1);
  background-color: var(--mat-progress-bar-track-color, var(--mat-sys-surface-variant));
}

.mdc-linear-progress__primary-bar {
  transform: scaleX(0);
}
.mdc-linear-progress--indeterminate .mdc-linear-progress__primary-bar {
  left: -145.166611%;
}
.mdc-linear-progress--indeterminate.mdc-linear-progress--animation-ready .mdc-linear-progress__primary-bar {
  animation: mdc-linear-progress-primary-indeterminate-translate calc(2s * var(--mat-progress-bar-animation-multiplier)) infinite linear;
}
.mdc-linear-progress--indeterminate.mdc-linear-progress--animation-ready .mdc-linear-progress__primary-bar > .mdc-linear-progress__bar-inner {
  animation: mdc-linear-progress-primary-indeterminate-scale calc(2s * var(--mat-progress-bar-animation-multiplier)) infinite linear;
}
[dir=rtl] .mdc-linear-progress.mdc-linear-progress--animation-ready .mdc-linear-progress__primary-bar {
  animation-name: mdc-linear-progress-primary-indeterminate-translate-reverse;
}
[dir=rtl] .mdc-linear-progress.mdc-linear-progress--indeterminate .mdc-linear-progress__primary-bar {
  right: -145.166611%;
  left: auto;
}

.mdc-linear-progress__secondary-bar {
  display: none;
}
.mdc-linear-progress--indeterminate .mdc-linear-progress__secondary-bar {
  left: -54.888891%;
  display: block;
}
.mdc-linear-progress--indeterminate.mdc-linear-progress--animation-ready .mdc-linear-progress__secondary-bar {
  animation: mdc-linear-progress-secondary-indeterminate-translate calc(2s * var(--mat-progress-bar-animation-multiplier)) infinite linear;
}
.mdc-linear-progress--indeterminate.mdc-linear-progress--animation-ready .mdc-linear-progress__secondary-bar > .mdc-linear-progress__bar-inner {
  animation: mdc-linear-progress-secondary-indeterminate-scale calc(2s * var(--mat-progress-bar-animation-multiplier)) infinite linear;
}
[dir=rtl] .mdc-linear-progress.mdc-linear-progress--animation-ready .mdc-linear-progress__secondary-bar {
  animation-name: mdc-linear-progress-secondary-indeterminate-translate-reverse;
}
[dir=rtl] .mdc-linear-progress.mdc-linear-progress--indeterminate .mdc-linear-progress__secondary-bar {
  right: -54.888891%;
  left: auto;
}

@keyframes mdc-linear-progress-buffering {
  from {
    transform: rotate(180deg) translateX(calc(var(--mat-progress-bar-track-height, 4px) * -2.5));
  }
}
@keyframes mdc-linear-progress-primary-indeterminate-translate {
  0% {
    transform: translateX(0);
  }
  20% {
    animation-timing-function: cubic-bezier(0.5, 0, 0.701732, 0.495819);
    transform: translateX(0);
  }
  59.15% {
    animation-timing-function: cubic-bezier(0.302435, 0.381352, 0.55, 0.956352);
    transform: translateX(83.67142%);
  }
  100% {
    transform: translateX(200.611057%);
  }
}
@keyframes mdc-linear-progress-primary-indeterminate-scale {
  0% {
    transform: scaleX(0.08);
  }
  36.65% {
    animation-timing-function: cubic-bezier(0.334731, 0.12482, 0.785844, 1);
    transform: scaleX(0.08);
  }
  69.15% {
    animation-timing-function: cubic-bezier(0.06, 0.11, 0.6, 1);
    transform: scaleX(0.661479);
  }
  100% {
    transform: scaleX(0.08);
  }
}
@keyframes mdc-linear-progress-secondary-indeterminate-translate {
  0% {
    animation-timing-function: cubic-bezier(0.15, 0, 0.515058, 0.409685);
    transform: translateX(0);
  }
  25% {
    animation-timing-function: cubic-bezier(0.31033, 0.284058, 0.8, 0.733712);
    transform: translateX(37.651913%);
  }
  48.35% {
    animation-timing-function: cubic-bezier(0.4, 0.627035, 0.6, 0.902026);
    transform: translateX(84.386165%);
  }
  100% {
    transform: translateX(160.277782%);
  }
}
@keyframes mdc-linear-progress-secondary-indeterminate-scale {
  0% {
    animation-timing-function: cubic-bezier(0.205028, 0.057051, 0.57661, 0.453971);
    transform: scaleX(0.08);
  }
  19.15% {
    animation-timing-function: cubic-bezier(0.152313, 0.196432, 0.648374, 1.004315);
    transform: scaleX(0.457104);
  }
  44.15% {
    animation-timing-function: cubic-bezier(0.257759, -0.003163, 0.211762, 1.38179);
    transform: scaleX(0.72796);
  }
  100% {
    transform: scaleX(0.08);
  }
}
@keyframes mdc-linear-progress-primary-indeterminate-translate-reverse {
  0% {
    transform: translateX(0);
  }
  20% {
    animation-timing-function: cubic-bezier(0.5, 0, 0.701732, 0.495819);
    transform: translateX(0);
  }
  59.15% {
    animation-timing-function: cubic-bezier(0.302435, 0.381352, 0.55, 0.956352);
    transform: translateX(-83.67142%);
  }
  100% {
    transform: translateX(-200.611057%);
  }
}
@keyframes mdc-linear-progress-secondary-indeterminate-translate-reverse {
  0% {
    animation-timing-function: cubic-bezier(0.15, 0, 0.515058, 0.409685);
    transform: translateX(0);
  }
  25% {
    animation-timing-function: cubic-bezier(0.31033, 0.284058, 0.8, 0.733712);
    transform: translateX(-37.651913%);
  }
  48.35% {
    animation-timing-function: cubic-bezier(0.4, 0.627035, 0.6, 0.902026);
    transform: translateX(-84.386165%);
  }
  100% {
    transform: translateX(-160.277782%);
  }
}
@keyframes mdc-linear-progress-buffering-reverse {
  from {
    transform: translateX(-10px);
  }
}
`],encapsulation:2})}return n})();function $s(n,r=0,e=100){return Math.max(r,Math.min(e,n))}var Ga=(()=>{class n{static \u0275fac=function(t){return new(t||n)};static \u0275mod=bE({type:n});static \u0275inj=cu$1({imports:[jo$1]})}return n})();var ym=["determinateSpinner"];function xm(n,r){if(n&1&&(Gu(),yi(0,"svg",11),Yp(1,"circle",12),jc$1()),n&2){let e=pD();Qp("viewBox",e._viewBox()),Wv(),dh$1("stroke-dasharray",e._strokeCircumference(),"px")("stroke-dashoffset",e._strokeCircumference()/2,"px")("stroke-width",e._circleStrokeWidth(),"%"),Qp("r",e._circleRadius());}}var wm=new x("mat-progress-spinner-default-options",{providedIn:"root",factory:()=>({diameter:Xs})}),Xs=100,km=10,Pv=(()=>{class n{_elementRef=T(Dr$1);_noopAnimations;get color(){return this._color||this._defaultColor}set color(e){this._color=e;}_color;_defaultColor="primary";_determinateCircle;constructor(){let e=T(wm),t=Ba$1(),i=this._elementRef.nativeElement;this._noopAnimations=t==="di-disabled"&&!!e&&!e._forceAnimations,this.mode=i.nodeName.toLowerCase()==="mat-spinner"?"indeterminate":"determinate",!this._noopAnimations&&t==="reduced-motion"&&i.classList.add("mat-progress-spinner-reduced-motion"),e&&(e.color&&(this.color=this._defaultColor=e.color),e.diameter&&(this.diameter=e.diameter),e.strokeWidth&&(this.strokeWidth=e.strokeWidth));}mode;get value(){return this.mode==="determinate"?this._value:0}set value(e){this._value=Math.max(0,Math.min(100,e||0));}_value=0;get diameter(){return this._diameter}set diameter(e){this._diameter=e||0;}_diameter=Xs;get strokeWidth(){return this._strokeWidth??this.diameter/10}set strokeWidth(e){this._strokeWidth=e||0;}_strokeWidth;_circleRadius(){return (this.diameter-km)/2}_viewBox(){let e=this._circleRadius()*2+this.strokeWidth;return `0 0 ${e} ${e}`}_strokeCircumference(){return 2*Math.PI*this._circleRadius()}_strokeDashOffset(){return this.mode==="determinate"?this._strokeCircumference()*(100-this._value)/100:null}_circleStrokeWidth(){return this.strokeWidth/this.diameter*100}static \u0275fac=function(t){return new(t||n)};static \u0275cmp=TE({type:n,selectors:[["mat-progress-spinner"],["mat-spinner"]],viewQuery:function(t,i){if(t&1&&sh$1(ym,5),t&2){let a;vD(a=ID())&&(i._determinateCircle=a.first);}},hostAttrs:["role","progressbar","tabindex","-1",1,"mat-mdc-progress-spinner","mdc-circular-progress"],hostVars:18,hostBindings:function(t,i){t&2&&(Qp("aria-valuemin",0)("aria-valuemax",100)("aria-valuenow",i.mode==="determinate"?i.value:null)("mode",i.mode),RD("mat-"+i.color),dh$1("width",i.diameter,"px")("height",i.diameter,"px")("--mat-progress-spinner-size",i.diameter+"px")("--mat-progress-spinner-active-indicator-width",i.diameter+"px"),fh$1("_mat-animation-noopable",i._noopAnimations)("mdc-circular-progress--indeterminate",i.mode==="indeterminate"));},inputs:{color:"color",mode:"mode",value:[2,"value","value",V0],diameter:[2,"diameter","diameter",V0],strokeWidth:[2,"strokeWidth","strokeWidth",V0]},exportAs:["matProgressSpinner"],decls:14,vars:11,consts:[["circle",""],["determinateSpinner",""],["aria-hidden","true",1,"mdc-circular-progress__determinate-container"],["xmlns","http://www.w3.org/2000/svg","focusable","false",1,"mdc-circular-progress__determinate-circle-graphic"],["cx","50%","cy","50%",1,"mdc-circular-progress__determinate-circle"],["aria-hidden","true",1,"mdc-circular-progress__indeterminate-container"],[1,"mdc-circular-progress__spinner-layer"],[1,"mdc-circular-progress__circle-clipper","mdc-circular-progress__circle-left"],[3,"ngTemplateOutlet"],[1,"mdc-circular-progress__gap-patch"],[1,"mdc-circular-progress__circle-clipper","mdc-circular-progress__circle-right"],["xmlns","http://www.w3.org/2000/svg","focusable","false",1,"mdc-circular-progress__indeterminate-circle-graphic"],["cx","50%","cy","50%"]],template:function(t,i){if(t&1&&($p(0,xm,2,8,"ng-template",null,0,uw),yi(2,"div",2,1),Gu(),yi(4,"svg",3),Yp(5,"circle",4),jc$1()(),zu(),yi(6,"div",5)(7,"div",6)(8,"div",7),Xp(9,8),jc$1(),yi(10,"div",9),Xp(11,8),jc$1(),yi(12,"div",10),Xp(13,8),jc$1()()()),t&2){let a=DD(1);Wv(4),Qp("viewBox",i._viewBox()),Wv(),dh$1("stroke-dasharray",i._strokeCircumference(),"px")("stroke-dashoffset",i._strokeDashOffset(),"px")("stroke-width",i._circleStrokeWidth(),"%"),Qp("r",i._circleRadius()),Wv(4),Zp("ngTemplateOutlet",a),Wv(2),Zp("ngTemplateOutlet",a),Wv(2),Zp("ngTemplateOutlet",a);}},dependencies:[jn$1],styles:[`.mat-mdc-progress-spinner {
  --mat-progress-spinner-animation-multiplier: 1;
  display: block;
  overflow: hidden;
  line-height: 0;
  position: relative;
  direction: ltr;
  transition: opacity 250ms cubic-bezier(0.4, 0, 0.6, 1);
}
.mat-mdc-progress-spinner circle {
  stroke-width: var(--mat-progress-spinner-active-indicator-width, 4px);
}
.mat-mdc-progress-spinner._mat-animation-noopable, .mat-mdc-progress-spinner._mat-animation-noopable .mdc-circular-progress__determinate-circle {
  transition: none !important;
}
.mat-mdc-progress-spinner._mat-animation-noopable .mdc-circular-progress__indeterminate-circle-graphic,
.mat-mdc-progress-spinner._mat-animation-noopable .mdc-circular-progress__spinner-layer,
.mat-mdc-progress-spinner._mat-animation-noopable .mdc-circular-progress__indeterminate-container {
  animation: none !important;
}
.mat-mdc-progress-spinner._mat-animation-noopable .mdc-circular-progress__indeterminate-container circle {
  stroke-dasharray: 0 !important;
}
@media (forced-colors: active) {
  .mat-mdc-progress-spinner .mdc-circular-progress__indeterminate-circle-graphic,
  .mat-mdc-progress-spinner .mdc-circular-progress__determinate-circle {
    stroke: currentColor;
    stroke: CanvasText;
  }
}

.mat-progress-spinner-reduced-motion {
  --mat-progress-spinner-animation-multiplier: 1.25;
}

.mdc-circular-progress__determinate-container,
.mdc-circular-progress__indeterminate-circle-graphic,
.mdc-circular-progress__indeterminate-container,
.mdc-circular-progress__spinner-layer {
  position: absolute;
  width: 100%;
  height: 100%;
}

.mdc-circular-progress__determinate-container {
  transform: rotate(-90deg);
}
.mdc-circular-progress--indeterminate .mdc-circular-progress__determinate-container {
  opacity: 0;
}

.mdc-circular-progress__indeterminate-container {
  font-size: 0;
  letter-spacing: 0;
  white-space: nowrap;
  opacity: 0;
}
.mdc-circular-progress--indeterminate .mdc-circular-progress__indeterminate-container {
  opacity: 1;
  animation: mdc-circular-progress-container-rotate calc(1568.2352941176ms * var(--mat-progress-spinner-animation-multiplier)) linear infinite;
}

.mdc-circular-progress__determinate-circle-graphic,
.mdc-circular-progress__indeterminate-circle-graphic {
  fill: transparent;
}

.mat-mdc-progress-spinner .mdc-circular-progress__determinate-circle,
.mat-mdc-progress-spinner .mdc-circular-progress__indeterminate-circle-graphic {
  stroke: var(--mat-progress-spinner-active-indicator-color, var(--mat-sys-primary));
}
@media (forced-colors: active) {
  .mat-mdc-progress-spinner .mdc-circular-progress__determinate-circle,
  .mat-mdc-progress-spinner .mdc-circular-progress__indeterminate-circle-graphic {
    stroke: CanvasText;
  }
}

.mdc-circular-progress__determinate-circle {
  transition: stroke-dashoffset 500ms cubic-bezier(0, 0, 0.2, 1);
}

.mdc-circular-progress__gap-patch {
  position: absolute;
  top: 0;
  left: 47.5%;
  box-sizing: border-box;
  width: 5%;
  height: 100%;
  overflow: hidden;
}

.mdc-circular-progress__gap-patch .mdc-circular-progress__indeterminate-circle-graphic {
  left: -900%;
  width: 2000%;
  transform: rotate(180deg);
}
.mdc-circular-progress__circle-clipper .mdc-circular-progress__indeterminate-circle-graphic {
  width: 200%;
}
.mdc-circular-progress__circle-right .mdc-circular-progress__indeterminate-circle-graphic {
  left: -100%;
}
.mdc-circular-progress--indeterminate .mdc-circular-progress__circle-left .mdc-circular-progress__indeterminate-circle-graphic {
  animation: mdc-circular-progress-left-spin calc(1333ms * var(--mat-progress-spinner-animation-multiplier)) cubic-bezier(0.4, 0, 0.2, 1) infinite both;
}
.mdc-circular-progress--indeterminate .mdc-circular-progress__circle-right .mdc-circular-progress__indeterminate-circle-graphic {
  animation: mdc-circular-progress-right-spin calc(1333ms * var(--mat-progress-spinner-animation-multiplier)) cubic-bezier(0.4, 0, 0.2, 1) infinite both;
}

.mdc-circular-progress__circle-clipper {
  display: inline-flex;
  position: relative;
  width: 50%;
  height: 100%;
  overflow: hidden;
}

.mdc-circular-progress--indeterminate .mdc-circular-progress__spinner-layer {
  animation: mdc-circular-progress-spinner-layer-rotate calc(5332ms * var(--mat-progress-spinner-animation-multiplier)) cubic-bezier(0.4, 0, 0.2, 1) infinite both;
}

@keyframes mdc-circular-progress-container-rotate {
  to {
    transform: rotate(360deg);
  }
}
@keyframes mdc-circular-progress-spinner-layer-rotate {
  12.5% {
    transform: rotate(135deg);
  }
  25% {
    transform: rotate(270deg);
  }
  37.5% {
    transform: rotate(405deg);
  }
  50% {
    transform: rotate(540deg);
  }
  62.5% {
    transform: rotate(675deg);
  }
  75% {
    transform: rotate(810deg);
  }
  87.5% {
    transform: rotate(945deg);
  }
  100% {
    transform: rotate(1080deg);
  }
}
@keyframes mdc-circular-progress-left-spin {
  from {
    transform: rotate(265deg);
  }
  50% {
    transform: rotate(130deg);
  }
  to {
    transform: rotate(265deg);
  }
}
@keyframes mdc-circular-progress-right-spin {
  from {
    transform: rotate(-265deg);
  }
  50% {
    transform: rotate(-130deg);
  }
  to {
    transform: rotate(-265deg);
  }
}
`],encapsulation:2})}return n})();var Ua=(()=>{class n{static \u0275fac=function(t){return new(t||n)};static \u0275mod=bE({type:n});static \u0275inj=cu$1({imports:[jo$1]})}return n})();var Cm=["input"],Dm=["formField"],Mm=["*"],On=class{source;value;constructor(r,e){this.source=r,this.value=e;}},Sm={provide:Xe,useExisting:vo(()=>Em),multi:true},Zs=new x("MatRadioGroup"),Im=new x("mat-radio-default-options",{providedIn:"root",factory:()=>({color:"accent",disabledInteractive:false})}),Em=(()=>{class n{_changeDetector=T(L0);_value=null;_name=T(Y).getId("mat-radio-group-");_selected=null;_isInitialized=false;_labelPosition="after";_disabled=false;_required=false;_buttonChanges;_controlValueAccessorChangeFn=()=>{};onTouched=()=>{};change=new $e;_radios;color;get name(){return this._name}set name(e){this._name=e,this._updateRadioButtonNames();}get labelPosition(){return this._labelPosition}set labelPosition(e){this._labelPosition=e==="before"?"before":"after",this._markRadiosForCheck();}get value(){return this._value}set value(e){this._value!==e&&(this._value=e,this._updateSelectedRadioFromValue(),this._checkSelectedRadioButton());}_checkSelectedRadioButton(){this._selected&&!this._selected.checked&&(this._selected.checked=true);}get selected(){return this._selected}set selected(e){this._selected=e,this.value=e?e.value:null,this._checkSelectedRadioButton();}get disabled(){return this._disabled}set disabled(e){this._disabled=e,this._markRadiosForCheck();}get required(){return this._required}set required(e){this._required=e,this._markRadiosForCheck();}get disabledInteractive(){return this._disabledInteractive}set disabledInteractive(e){this._disabledInteractive=e,this._markRadiosForCheck();}_disabledInteractive=false;ngAfterContentInit(){this._isInitialized=true,this._buttonChanges=this._radios.changes.subscribe(()=>{this.selected&&!this._radios.find(e=>e===this.selected)&&(this._selected=null);});}ngOnDestroy(){this._buttonChanges?.unsubscribe();}_touch(){this.onTouched&&this.onTouched();}_updateRadioButtonNames(){this._radios&&this._radios.forEach(e=>{e.name=this.name,e._markForCheck();});}_updateSelectedRadioFromValue(){let e=this._selected!==null&&this._selected.value===this._value;this._radios&&!e&&(this._selected=null,this._radios.forEach(t=>{t.checked=this.value===t.value,t.checked&&(this._selected=t);}));}_emitChangeEvent(){this._isInitialized&&this.change.emit(new On(this._selected,this._value));}_markRadiosForCheck(){this._radios&&this._radios.forEach(e=>e._markForCheck());}writeValue(e){this.value=e,this._changeDetector.markForCheck();}registerOnChange(e){this._controlValueAccessorChangeFn=e;}registerOnTouched(e){this.onTouched=e;}setDisabledState(e){this.disabled=e,this._changeDetector.markForCheck();}static \u0275fac=function(t){return new(t||n)};static \u0275dir=SE({type:n,selectors:[["mat-radio-group"]],contentQueries:function(t,i,a){if(t&1&&ih$1(a,Js,5),t&2){let o;vD(o=ID())&&(i._radios=o);}},hostAttrs:["role","radiogroup",1,"mat-mdc-radio-group"],inputs:{color:"color",name:"name",labelPosition:"labelPosition",value:"value",selected:"selected",disabled:[2,"disabled","disabled",j0],required:[2,"required","required",j0],disabledInteractive:[2,"disabledInteractive","disabledInteractive",j0]},outputs:{change:"change"},exportAs:["matRadioGroup"],features:[JD([Sm,{provide:Zs,useExisting:n}])]})}return n})(),Js=(()=>{class n{_elementRef=T(Dr$1);_changeDetector=T(L0);_focusMonitor=T(le);_radioDispatcher=T(qt);_defaultOptions=T(Im,{optional:true});_ngZone=T(Se);_renderer=T(MI);_uniqueId=T(Y).getId("mat-radio-");_cleanupClick;id=this._uniqueId;name;ariaLabel;ariaLabelledby;ariaDescribedby;disableRipple=false;tabIndex=0;get checked(){return this._checked}set checked(e){this._checked!==e&&(this._checked=e,e&&this.radioGroup&&this.radioGroup.value!==this.value?this.radioGroup.selected=this:!e&&this.radioGroup&&this.radioGroup.value===this.value&&(this.radioGroup.selected=null),e&&this._radioDispatcher.notify(this.id,this.name),this._changeDetector.markForCheck());}get value(){return this._value}set value(e){this._value!==e&&(this._value=e,this.radioGroup!==null&&(this.checked||(this.checked=this.radioGroup.value===e),this.checked&&(this.radioGroup.selected=this)));}get labelPosition(){return this._labelPosition||this.radioGroup&&this.radioGroup.labelPosition||"after"}set labelPosition(e){this._labelPosition=e;}_labelPosition;get disabled(){return this._disabled||this.radioGroup!==null&&this.radioGroup.disabled}set disabled(e){this._setDisabled(e);}get required(){return this._required||this.radioGroup&&this.radioGroup.required}set required(e){e!==this._required&&this._changeDetector.markForCheck(),this._required=e;}get color(){return this._color||this.radioGroup&&this.radioGroup.color||this._defaultOptions&&this._defaultOptions.color||"accent"}set color(e){this._color=e;}_color;get disabledInteractive(){return this._disabledInteractive||this.radioGroup!==null&&this.radioGroup.disabledInteractive}set disabledInteractive(e){this._disabledInteractive=e;}_disabledInteractive;change=new $e;radioGroup;get inputId(){return `${this.id||this._uniqueId}-input`}_checked=false;_disabled=false;_required=false;_value=null;_removeUniqueSelectionListener=()=>{};_previousTabIndex;_inputElement;_rippleTrigger;_noopAnimations=B();_injector=T(ge);constructor(){T(W$1).load(bn$1);let e=T(Zs,{optional:true}),t=T(new Ah$1("tabindex"),{optional:true});this.radioGroup=e,this._disabledInteractive=this._defaultOptions?.disabledInteractive??false,t&&(this.tabIndex=V0(t,0));}focus(e,t){t?this._focusMonitor.focusVia(this._inputElement,t,e):this._inputElement.nativeElement.focus(e);}_markForCheck(){this._changeDetector.markForCheck();}ngOnInit(){this.radioGroup&&(this.checked=this.radioGroup.value===this._value,this.checked&&(this.radioGroup.selected=this),this.name=this.radioGroup.name),this._removeUniqueSelectionListener=this._radioDispatcher.listen((e,t)=>{e!==this.id&&t===this.name&&(this.checked=false);});}ngDoCheck(){this._updateTabIndex();}ngAfterViewInit(){this._updateTabIndex(),this._focusMonitor.monitor(this._elementRef,true).subscribe(e=>{!e&&this.radioGroup&&this.radioGroup._touch();}),this._ngZone.runOutsideAngular(()=>{this._cleanupClick=this._renderer.listen(this._inputElement.nativeElement,"click",this._onInputClick);});}ngOnDestroy(){this._cleanupClick?.(),this._focusMonitor.stopMonitoring(this._elementRef),this._removeUniqueSelectionListener();}_emitChangeEvent(){this.change.emit(new On(this,this._value));}_isRippleDisabled(){return this.disableRipple||this.disabled}_onInputInteraction(e){if(e.stopPropagation(),!this.checked&&!this.disabled){let t=this.radioGroup&&this.value!==this.radioGroup.value;this.checked=true,this._emitChangeEvent(),this.radioGroup&&(this.radioGroup._controlValueAccessorChangeFn(this.value),t&&this.radioGroup._emitChangeEvent());}}_onTouchTargetClick(e){this._onInputInteraction(e),(!this.disabled||this.disabledInteractive)&&this._inputElement?.nativeElement.focus();}_setDisabled(e){this._disabled!==e&&(this._disabled=e,this._changeDetector.markForCheck());}_onInputClick=e=>{this.disabled&&this.disabledInteractive&&e.preventDefault();};_updateTabIndex(){let e=this.radioGroup,t;if(!e||!e.selected||this.disabled?t=this.tabIndex:t=e.selected===this?this.tabIndex:-1,t!==this._previousTabIndex){let i=this._inputElement?.nativeElement;i&&(i.setAttribute("tabindex",t+""),this._previousTabIndex=t,Dv(()=>{queueMicrotask(()=>{e&&e.selected&&e.selected!==this&&document.activeElement===i&&(e.selected?._inputElement.nativeElement.focus(),document.activeElement===i&&this._inputElement.nativeElement.blur());});},{injector:this._injector}));}}static \u0275fac=function(t){return new(t||n)};static \u0275cmp=TE({type:n,selectors:[["mat-radio-button"]],viewQuery:function(t,i){if(t&1&&sh$1(Cm,5)(Dm,7,Dr$1),t&2){let a;vD(a=ID())&&(i._inputElement=a.first),vD(a=ID())&&(i._rippleTrigger=a.first);}},hostAttrs:[1,"mat-mdc-radio-button"],hostVars:19,hostBindings:function(t,i){t&1&&nh$1("focus",function(){return i._inputElement.nativeElement.focus()}),t&2&&(Qp("id",i.id)("tabindex",null)("aria-label",null)("aria-labelledby",null)("aria-describedby",null),fh$1("mat-primary",i.color==="primary")("mat-accent",i.color==="accent")("mat-warn",i.color==="warn")("mat-mdc-radio-checked",i.checked)("mat-mdc-radio-disabled",i.disabled)("mat-mdc-radio-disabled-interactive",i.disabledInteractive)("_mat-animation-noopable",i._noopAnimations));},inputs:{id:"id",name:"name",ariaLabel:[0,"aria-label","ariaLabel"],ariaLabelledby:[0,"aria-labelledby","ariaLabelledby"],ariaDescribedby:[0,"aria-describedby","ariaDescribedby"],disableRipple:[2,"disableRipple","disableRipple",j0],tabIndex:[2,"tabIndex","tabIndex",e=>e==null?0:V0(e)],checked:[2,"checked","checked",j0],value:"value",labelPosition:"labelPosition",disabled:[2,"disabled","disabled",j0],required:[2,"required","required",j0],color:"color",disabledInteractive:[2,"disabledInteractive","disabledInteractive",j0]},outputs:{change:"change"},exportAs:["matRadioButton"],ngContentSelectors:Mm,decls:13,vars:17,consts:[["formField",""],["input",""],["mat-internal-form-field","",3,"labelPosition"],[1,"mdc-radio"],["aria-hidden","true",1,"mat-mdc-radio-touch-target",3,"click"],["type","radio","aria-invalid","false",1,"mdc-radio__native-control",3,"change","id","checked","disabled","required"],["aria-hidden","true",1,"mdc-radio__background"],[1,"mdc-radio__outer-circle"],[1,"mdc-radio__inner-circle"],["mat-ripple","","aria-hidden","true",1,"mat-radio-ripple","mat-focus-indicator",3,"matRippleTrigger","matRippleDisabled","matRippleCentered"],[1,"mat-ripple-element","mat-radio-persistent-ripple"],[1,"mdc-label",3,"for"]],template:function(t,i){t&1&&(gD(),yi(0,"div",2,0)(2,"div",3)(3,"div",4),nh$1("click",function(o){return i._onTouchTargetClick(o)}),jc$1(),yi(4,"input",5,1),nh$1("change",function(o){return i._onInputInteraction(o)}),jc$1(),yi(6,"div",6),Yp(7,"div",7)(8,"div",8),jc$1(),yi(9,"div",9),Yp(10,"div",10),jc$1()(),yi(11,"label",11),mD(12),jc$1()()),t&2&&(Zp("labelPosition",i.labelPosition),Wv(2),fh$1("mdc-radio--disabled",i.disabled),Wv(2),Zp("id",i.inputId)("checked",i.checked)("disabled",i.disabled&&!i.disabledInteractive)("required",i.required),Qp("name",i.name)("value",i.value)("aria-label",i.ariaLabel)("aria-labelledby",i.ariaLabelledby)("aria-describedby",i.ariaDescribedby)("aria-disabled",i.disabled&&i.disabledInteractive?"true":null),Wv(5),Zp("matRippleTrigger",i._rippleTrigger.nativeElement)("matRippleDisabled",i._isRippleDisabled())("matRippleCentered",true),Wv(2),Zp("for",i.inputId));},dependencies:[rr$1,bf],styles:[`.mat-mdc-radio-button {
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
`],encapsulation:2})}return n})(),Wa=(()=>{class n{static \u0275fac=function(t){return new(t||n)};static \u0275mod=bE({type:n});static \u0275inj=cu$1({imports:[Ye,Js,jo$1]})}return n})();var Rm=[[["caption"]],[["colgroup"],["col"]],"*"],Fm=["caption","colgroup, col","*"];function Om(n,r){n&1&&mD(0,2);}function Pm(n,r){n&1&&(yi(0,"thead",0),Xp(1,1),jc$1(),yi(2,"tbody",0),Xp(3,2)(4,3),jc$1(),yi(5,"tfoot",0),Xp(6,4),jc$1());}function Vm(n,r){n&1&&Xp(0,1)(1,2)(2,3)(3,4);}var _t=new x("CDK_TABLE");var Ln=(()=>{class n{template=T(hr$1);static \u0275fac=function(t){return new(t||n)};static \u0275dir=SE({type:n,selectors:[["","cdkCellDef",""]]})}return n})(),Nn=(()=>{class n{template=T(hr$1);static \u0275fac=function(t){return new(t||n)};static \u0275dir=SE({type:n,selectors:[["","cdkHeaderCellDef",""]]})}return n})(),il=(()=>{class n{template=T(hr$1);static \u0275fac=function(t){return new(t||n)};static \u0275dir=SE({type:n,selectors:[["","cdkFooterCellDef",""]]})}return n})(),fi=(()=>{class n{_table=T(_t,{optional:true});_hasStickyChanged=false;get name(){return this._name}set name(e){this._setNameInput(e);}_name;get sticky(){return this._sticky}set sticky(e){e!==this._sticky&&(this._sticky=e,this._hasStickyChanged=true);}_sticky=false;get stickyEnd(){return this._stickyEnd}set stickyEnd(e){e!==this._stickyEnd&&(this._stickyEnd=e,this._hasStickyChanged=true);}_stickyEnd=false;cell;headerCell;footerCell;cssClassFriendlyName;_columnCssClassName;hasStickyChanged(){let e=this._hasStickyChanged;return this.resetStickyChanged(),e}resetStickyChanged(){this._hasStickyChanged=false;}_updateColumnCssClassName(){this._columnCssClassName=[`cdk-column-${this.cssClassFriendlyName}`];}_setNameInput(e){e&&(this._name=e,this.cssClassFriendlyName=e.replace(/[^a-z0-9_-]/gi,"-"),this._updateColumnCssClassName());}static \u0275fac=function(t){return new(t||n)};static \u0275dir=SE({type:n,selectors:[["","cdkColumnDef",""]],contentQueries:function(t,i,a){if(t&1&&ih$1(a,Ln,5)(a,Nn,5)(a,il,5),t&2){let o;vD(o=ID())&&(i.cell=o.first),vD(o=ID())&&(i.headerCell=o.first),vD(o=ID())&&(i.footerCell=o.first);}},inputs:{name:[0,"cdkColumnDef","name"],sticky:[2,"sticky","sticky",j0],stickyEnd:[2,"stickyEnd","stickyEnd",j0]}})}return n})(),Vn=class{constructor(r,e){e.nativeElement.classList.add(...r._columnCssClassName);}},nl=(()=>{class n extends Vn{constructor(){super(T(fi),T(Dr$1));}static \u0275fac=function(t){return new(t||n)};static \u0275dir=SE({type:n,selectors:[["cdk-header-cell"],["th","cdk-header-cell",""]],hostAttrs:["role","columnheader",1,"cdk-header-cell"],features:[Hp$1]})}return n})();var al=(()=>{class n extends Vn{constructor(){let e=T(fi),t=T(Dr$1);super(e,t);let i=e._table?._getCellRole();i&&t.nativeElement.setAttribute("role",i);}static \u0275fac=function(t){return new(t||n)};static \u0275dir=SE({type:n,selectors:[["cdk-cell"],["td","cdk-cell",""]],hostAttrs:[1,"cdk-cell"],features:[Hp$1]})}return n})();var Qa=(()=>{class n{template=T(hr$1);_differs=T(_w);columns;_columnsDiffer;ngOnChanges(e){if(!this._columnsDiffer){let t=e.columns&&e.columns.currentValue||[];this._columnsDiffer=this._differs.find(t).create(),this._columnsDiffer.diff(t);}}getColumnsDiff(){return this._columnsDiffer.diff(this.columns)}extractCellTemplate(e){return this instanceof Bi?e.headerCell.template:this instanceof Ya?e.footerCell.template:e.cell.template}static \u0275fac=function(t){return new(t||n)};static \u0275dir=SE({type:n,features:[zm$1]})}return n})(),Bi=(()=>{class n extends Qa{_table=T(_t,{optional:true});_hasStickyChanged=false;get sticky(){return this._sticky}set sticky(e){e!==this._sticky&&(this._sticky=e,this._hasStickyChanged=true);}_sticky=false;ngOnChanges(e){super.ngOnChanges(e);}hasStickyChanged(){let e=this._hasStickyChanged;return this.resetStickyChanged(),e}resetStickyChanged(){this._hasStickyChanged=false;}static \u0275fac=(()=>{let e;return function(i){return (e||(e=fy(n)))(i||n)}})();static \u0275dir=SE({type:n,selectors:[["","cdkHeaderRowDef",""]],inputs:{columns:[0,"cdkHeaderRowDef","columns"],sticky:[2,"cdkHeaderRowDefSticky","sticky",j0]},features:[Hp$1,zm$1]})}return n})(),Ya=(()=>{class n extends Qa{_table=T(_t,{optional:true});_hasStickyChanged=false;get sticky(){return this._sticky}set sticky(e){e!==this._sticky&&(this._sticky=e,this._hasStickyChanged=true);}_sticky=false;ngOnChanges(e){super.ngOnChanges(e);}hasStickyChanged(){let e=this._hasStickyChanged;return this.resetStickyChanged(),e}resetStickyChanged(){this._hasStickyChanged=false;}static \u0275fac=(()=>{let e;return function(i){return (e||(e=fy(n)))(i||n)}})();static \u0275dir=SE({type:n,selectors:[["","cdkFooterRowDef",""]],inputs:{columns:[0,"cdkFooterRowDef","columns"],sticky:[2,"cdkFooterRowDefSticky","sticky",j0]},features:[Hp$1,zm$1]})}return n})(),Bn=(()=>{class n extends Qa{_table=T(_t,{optional:true});when;static \u0275fac=(()=>{let e;return function(i){return (e||(e=fy(n)))(i||n)}})();static \u0275dir=SE({type:n,selectors:[["","cdkRowDef",""]],inputs:{columns:[0,"cdkRowDefColumns","columns"],when:[0,"cdkRowDefWhen","when"]},features:[Hp$1]})}return n})(),Yt=(()=>{class n{_viewContainer=T(Ai$1);cells;context;static mostRecentCellOutlet=null;constructor(){n.mostRecentCellOutlet=this;}ngOnDestroy(){n.mostRecentCellOutlet===this&&(n.mostRecentCellOutlet=null);}static \u0275fac=function(t){return new(t||n)};static \u0275dir=SE({type:n,selectors:[["","cdkCellOutlet",""]]})}return n})(),Ka=(()=>{class n{static \u0275fac=function(t){return new(t||n)};static \u0275cmp=TE({type:n,selectors:[["cdk-header-row"],["tr","cdk-header-row",""]],hostAttrs:["role","row",1,"cdk-header-row"],decls:1,vars:0,consts:[["cdkCellOutlet",""]],template:function(t,i){t&1&&Xp(0,0);},dependencies:[Yt],encapsulation:2,changeDetection:1})}return n})();var $a=(()=>{class n{static \u0275fac=function(t){return new(t||n)};static \u0275cmp=TE({type:n,selectors:[["cdk-row"],["tr","cdk-row",""]],hostAttrs:["role","row",1,"cdk-row"],decls:1,vars:0,consts:[["cdkCellOutlet",""]],template:function(t,i){t&1&&Xp(0,0);},dependencies:[Yt],encapsulation:2,changeDetection:1})}return n})(),rl=(()=>{class n{templateRef=T(hr$1);_contentClassNames=["cdk-no-data-row","cdk-row"];_cellClassNames=["cdk-cell","cdk-no-data-cell"];_cellSelector="td, cdk-cell, [cdk-cell], .cdk-cell";static \u0275fac=function(t){return new(t||n)};static \u0275dir=SE({type:n,selectors:[["ng-template","cdkNoDataRow",""]]})}return n})(),el=["top","bottom","left","right"],qa=class{_isNativeHtmlTable;_stickCellCss;_isBrowser;_needsPositionStickyOnElement;direction;_positionListener;_tableInjector;_elemSizeCache=new WeakMap;_resizeObserver=globalThis?.ResizeObserver?new globalThis.ResizeObserver(r=>this._updateCachedSizes(r)):null;_updatedStickyColumnsParamsToReplay=[];_stickyColumnsReplayTimeout=null;_cachedCellWidths=[];_borderCellCss;_destroyed=false;constructor(r,e,t=true,i=true,a,o,c){this._isNativeHtmlTable=r,this._stickCellCss=e,this._isBrowser=t,this._needsPositionStickyOnElement=i,this.direction=a,this._positionListener=o,this._tableInjector=c,this._borderCellCss={top:`${e}-border-elem-top`,bottom:`${e}-border-elem-bottom`,left:`${e}-border-elem-left`,right:`${e}-border-elem-right`};}clearStickyPositioning(r,e){(e.includes("left")||e.includes("right"))&&this._removeFromStickyColumnReplayQueue(r);let t=[];for(let i of r)i.nodeType===i.ELEMENT_NODE&&t.push(i,...Array.from(i.children));Dv({write:()=>{for(let i of t)this._removeStickyStyle(i,e);}},{injector:this._tableInjector});}updateStickyColumns(r,e,t,i=true,a=true){if(!r.length||!this._isBrowser||!(e.some(mt=>mt)||t.some(mt=>mt))){this._positionListener?.stickyColumnsUpdated({sizes:[]}),this._positionListener?.stickyEndColumnsUpdated({sizes:[]});return}let o=r[0],c=o.children.length,y=this.direction==="rtl",F=y?"right":"left",H=y?"left":"right",De=e.lastIndexOf(true),_e=t.indexOf(true),Me,ji,Gi;a&&this._updateStickyColumnReplayQueue({rows:[...r],stickyStartStates:[...e],stickyEndStates:[...t]}),Dv({earlyRead:()=>{Me=this._getCellWidths(o,i),ji=this._getStickyStartColumnPositions(Me,e),Gi=this._getStickyEndColumnPositions(Me,t);},write:()=>{for(let mt of r)for(let et=0;et<c;et++){let jr=mt.children[et];e[et]&&this._addStickyStyle(jr,F,ji[et],et===De),t[et]&&this._addStickyStyle(jr,H,Gi[et],et===_e);}this._positionListener&&Me.some(mt=>!!mt)&&(this._positionListener.stickyColumnsUpdated({sizes:De===-1?[]:Me.slice(0,De+1).map((mt,et)=>e[et]?mt:null)}),this._positionListener.stickyEndColumnsUpdated({sizes:_e===-1?[]:Me.slice(_e).map((mt,et)=>t[et+_e]?mt:null).reverse()}));}},{injector:this._tableInjector});}stickRows(r,e,t){if(!this._isBrowser)return;let i=t==="bottom"?r.slice().reverse():r,a=t==="bottom"?e.slice().reverse():e,o=[],c=[],y=[];Dv({earlyRead:()=>{for(let F=0,H=0;F<i.length;F++){if(!a[F])continue;o[F]=H;let De=i[F];y[F]=this._isNativeHtmlTable?Array.from(De.children):[De];let _e=this._retrieveElementSize(De).height;H+=_e,c[F]=_e;}},write:()=>{let F=a.lastIndexOf(true);for(let H=0;H<i.length;H++){if(!a[H])continue;let De=o[H],_e=H===F;for(let Me of y[H])this._addStickyStyle(Me,t,De,_e);}t==="top"?this._positionListener?.stickyHeaderRowsUpdated({sizes:c,offsets:o,elements:y}):this._positionListener?.stickyFooterRowsUpdated({sizes:c,offsets:o,elements:y});}},{injector:this._tableInjector});}updateStickyFooterContainer(r,e){this._isNativeHtmlTable&&Dv({write:()=>{let t=r.querySelector("tfoot");t&&(e.some(i=>!i)?this._removeStickyStyle(t,["bottom"]):this._addStickyStyle(t,"bottom",0,false));}},{injector:this._tableInjector});}destroy(){this._stickyColumnsReplayTimeout&&clearTimeout(this._stickyColumnsReplayTimeout),this._resizeObserver?.disconnect(),this._destroyed=true;}_removeStickyStyle(r,e){if(!r.classList.contains(this._stickCellCss))return;for(let i of e)r.style[i]="",r.classList.remove(this._borderCellCss[i]);el.some(i=>e.indexOf(i)===-1&&r.style[i])?r.style.zIndex=this._getCalculatedZIndex(r):(r.style.zIndex="",this._needsPositionStickyOnElement&&(r.style.position=""),r.classList.remove(this._stickCellCss));}_addStickyStyle(r,e,t,i){r.classList.add(this._stickCellCss),i&&r.classList.add(this._borderCellCss[e]),r.style[e]=`${t}px`,r.style.zIndex=this._getCalculatedZIndex(r),this._needsPositionStickyOnElement&&(r.style.cssText+="position: -webkit-sticky; position: sticky; ");}_getCalculatedZIndex(r){let e={top:100,bottom:10,left:1,right:1},t=0;for(let i of el)r.style[i]&&(t+=e[i]);return t?`${t}`:""}_getCellWidths(r,e=true){if(!e&&this._cachedCellWidths.length)return this._cachedCellWidths;let t=[],i=r.children;for(let a=0;a<i.length;a++){let o=i[a];t.push(this._retrieveElementSize(o).width);}return this._cachedCellWidths=t,t}_getStickyStartColumnPositions(r,e){let t=[],i=0;for(let a=0;a<r.length;a++)e[a]&&(t[a]=i,i+=r[a]);return t}_getStickyEndColumnPositions(r,e){let t=[],i=0;for(let a=r.length;a>0;a--)e[a]&&(t[a]=i,i+=r[a]);return t}_retrieveElementSize(r){let e=this._elemSizeCache.get(r);if(e)return e;let t=r.getBoundingClientRect(),i={width:t.width,height:t.height};return this._resizeObserver&&(this._elemSizeCache.set(r,i),this._resizeObserver.observe(r,{box:"border-box"})),i}_updateStickyColumnReplayQueue(r){this._removeFromStickyColumnReplayQueue(r.rows),this._stickyColumnsReplayTimeout||this._updatedStickyColumnsParamsToReplay.push(r);}_removeFromStickyColumnReplayQueue(r){let e=new Set(r);for(let t of this._updatedStickyColumnsParamsToReplay)t.rows=t.rows.filter(i=>!e.has(i));this._updatedStickyColumnsParamsToReplay=this._updatedStickyColumnsParamsToReplay.filter(t=>!!t.rows.length);}_updateCachedSizes(r){let e=false;for(let t of r){let i=t.borderBoxSize?.length?{width:t.borderBoxSize[0].inlineSize,height:t.borderBoxSize[0].blockSize}:{width:t.contentRect.width,height:t.contentRect.height};i.width!==this._elemSizeCache.get(t.target)?.width&&Lm(t.target)&&(e=true),this._elemSizeCache.set(t.target,i);}e&&this._updatedStickyColumnsParamsToReplay.length&&(this._stickyColumnsReplayTimeout&&clearTimeout(this._stickyColumnsReplayTimeout),this._stickyColumnsReplayTimeout=setTimeout(()=>{if(!this._destroyed){for(let t of this._updatedStickyColumnsParamsToReplay)this.updateStickyColumns(t.rows,t.stickyStartStates,t.stickyEndStates,true,false);this._updatedStickyColumnsParamsToReplay=[],this._stickyColumnsReplayTimeout=null;}},0));}};function Lm(n){return ["cdk-cell","cdk-header-cell","cdk-footer-cell"].some(r=>n.classList.contains(r))}var Ni=new x("STICKY_POSITIONING_LISTENER");var Xa=(()=>{class n{viewContainer=T(Ai$1);elementRef=T(Dr$1);constructor(){let e=T(_t);e._rowOutlet=this,e._outletAssigned();}static \u0275fac=function(t){return new(t||n)};static \u0275dir=SE({type:n,selectors:[["","rowOutlet",""]]})}return n})(),Za=(()=>{class n{viewContainer=T(Ai$1);elementRef=T(Dr$1);constructor(){let e=T(_t);e._headerRowOutlet=this,e._outletAssigned();}static \u0275fac=function(t){return new(t||n)};static \u0275dir=SE({type:n,selectors:[["","headerRowOutlet",""]]})}return n})(),Ja=(()=>{class n{viewContainer=T(Ai$1);elementRef=T(Dr$1);constructor(){let e=T(_t);e._footerRowOutlet=this,e._outletAssigned();}static \u0275fac=function(t){return new(t||n)};static \u0275dir=SE({type:n,selectors:[["","footerRowOutlet",""]]})}return n})(),er=(()=>{class n{viewContainer=T(Ai$1);elementRef=T(Dr$1);constructor(){let e=T(_t);e._noDataRowOutlet=this,e._outletAssigned();}static \u0275fac=function(t){return new(t||n)};static \u0275dir=SE({type:n,selectors:[["","noDataRowOutlet",""]]})}return n})(),tr=(()=>{class n{_differs=T(_w);_changeDetectorRef=T(L0);_elementRef=T(Dr$1);_dir=T(Er$2,{optional:true});_platform=T(D);_viewRepeater;_viewportRuler=T(he);_injector=T(ge);_virtualScrollViewport=T(Au,{optional:true,host:true});_positionListener=T(Ni,{optional:true})||T(Ni,{optional:true,skipSelf:true});_document=T(or$1);_data;_renderedRange;_onDestroy=new ne;_renderRows;_renderChangeSubscription=null;_columnDefsByName=new Map;_rowDefs;_headerRowDefs;_footerRowDefs;_dataDiffer;_defaultRowDef=null;_customColumnDefs=new Set;_customRowDefs=new Set;_customHeaderRowDefs=new Set;_customFooterRowDefs=new Set;_customNoDataRow=null;_headerRowDefChanged=true;_footerRowDefChanged=true;_stickyColumnStylesNeedReset=true;_forceRecalculateCellWidths=true;_cachedRenderRowsMap=new Map;_isNativeHtmlTable;_stickyStyler;stickyCssClass="cdk-table-sticky";needsPositionStickyOnElement=true;_isServer;_isShowingNoDataRow=false;_hasAllOutlets=false;_hasInitialized=false;_headerRowStickyUpdates=new ne;_footerRowStickyUpdates=new ne;_disableVirtualScrolling=false;_getCellRole(){if(this._cellRoleInternal===void 0){let e=this._elementRef.nativeElement.getAttribute("role");return e==="grid"||e==="treegrid"?"gridcell":"cell"}return this._cellRoleInternal}_cellRoleInternal=void 0;get trackBy(){return this._trackByFn}set trackBy(e){this._trackByFn=e;}_trackByFn;get dataSource(){return this._dataSource}set dataSource(e){this._dataSource!==e&&(this._switchDataSource(e),this._changeDetectorRef.markForCheck());}_dataSource;_dataSourceChanges=new ne;_dataStream=new ne;get multiTemplateDataRows(){return this._multiTemplateDataRows}set multiTemplateDataRows(e){this._multiTemplateDataRows=e,this._rowOutlet&&this._rowOutlet.viewContainer.length&&(this._forceRenderDataRows(),this.updateStickyColumnStyles());}_multiTemplateDataRows=false;get fixedLayout(){return this._virtualScrollEnabled()?true:this._fixedLayout}set fixedLayout(e){this._fixedLayout=e,this._forceRecalculateCellWidths=true,this._stickyColumnStylesNeedReset=true;}_fixedLayout=false;recycleRows=false;contentChanged=new $e;viewChange=new kn$1({start:0,end:Number.MAX_VALUE});_rowOutlet;_headerRowOutlet;_footerRowOutlet;_noDataRowOutlet;_contentColumnDefs;_contentRowDefs;_contentHeaderRowDefs;_contentFooterRowDefs;_noDataRow;get renderedRows(){return this._renderRows}constructor(){T(new Ah$1("role"),{optional:true})||this._elementRef.nativeElement.setAttribute("role","table"),this._isServer=!this._platform.isBrowser,this._isNativeHtmlTable=this._elementRef.nativeElement.nodeName==="TABLE",this._dataDiffer=this._differs.find([]).create((t,i)=>this.trackBy?this.trackBy(i.dataIndex,i.data):i);}ngOnInit(){this._setupStickyStyler(),this._viewportRuler.change().pipe(Bl$1(this._onDestroy)).subscribe(()=>{this._forceRecalculateCellWidths=true;});}ngAfterContentInit(){this._viewRepeater=this.recycleRows||this._virtualScrollEnabled()?new lr$1:new Tn,this._virtualScrollEnabled()&&this._setupVirtualScrolling(this._virtualScrollViewport),this._hasInitialized=true;}ngAfterContentChecked(){this._canRender()&&this._render();}ngOnDestroy(){this._stickyStyler?.destroy(),[this._rowOutlet?.viewContainer,this._headerRowOutlet?.viewContainer,this._footerRowOutlet?.viewContainer,this._cachedRenderRowsMap,this._customColumnDefs,this._customRowDefs,this._customHeaderRowDefs,this._customFooterRowDefs,this._columnDefsByName].forEach(e=>{e?.clear();}),this._headerRowDefs=[],this._footerRowDefs=[],this._defaultRowDef=null,this._headerRowStickyUpdates.complete(),this._footerRowStickyUpdates.complete(),this._onDestroy.next(),this._onDestroy.complete(),Qm$1(this.dataSource)&&this.dataSource.disconnect(this);}renderRows(){this._renderRows=this._getAllRenderRows();let e=this._dataDiffer.diff(this._renderRows);if(!e){this._updateNoDataRow(),this.contentChanged.next();return}let t=this._rowOutlet.viewContainer;this._viewRepeater.applyChanges(e,t,(i,a,o)=>this._getEmbeddedViewArgs(i.item,o),i=>i.item.data,i=>{i.operation===Ne.INSERTED&&i.context&&this._renderCellTemplateForItem(i.record.item.rowDef,i.context);}),this._updateRowIndexContext(),e.forEachIdentityChange(i=>{let a=t.get(i.currentIndex);a.context.$implicit=i.item.data;}),this._updateNoDataRow(),this.contentChanged.next(),this.updateStickyColumnStyles();}addColumnDef(e){this._customColumnDefs.add(e);}removeColumnDef(e){this._customColumnDefs.delete(e);}addRowDef(e){this._customRowDefs.add(e);}removeRowDef(e){this._customRowDefs.delete(e);}addHeaderRowDef(e){this._customHeaderRowDefs.add(e),this._headerRowDefChanged=true;}removeHeaderRowDef(e){this._customHeaderRowDefs.delete(e),this._headerRowDefChanged=true;}addFooterRowDef(e){this._customFooterRowDefs.add(e),this._footerRowDefChanged=true;}removeFooterRowDef(e){this._customFooterRowDefs.delete(e),this._footerRowDefChanged=true;}setNoDataRow(e){this._customNoDataRow=e;}updateStickyHeaderRowStyles(){let e=this._getRenderedRows(this._headerRowOutlet);if(this._isNativeHtmlTable){let i=tl(this._headerRowOutlet,"thead");i&&(i.style.display=e.length?"":"none");}let t=this._headerRowDefs.map(i=>i.sticky);this._stickyStyler.clearStickyPositioning(e,["top"]),this._stickyStyler.stickRows(e,t,"top"),this._headerRowDefs.forEach(i=>i.resetStickyChanged());}updateStickyFooterRowStyles(){let e=this._getRenderedRows(this._footerRowOutlet);if(this._isNativeHtmlTable){let i=tl(this._footerRowOutlet,"tfoot");i&&(i.style.display=e.length?"":"none");}let t=this._footerRowDefs.map(i=>i.sticky);this._stickyStyler.clearStickyPositioning(e,["bottom"]),this._stickyStyler.stickRows(e,t,"bottom"),this._stickyStyler.updateStickyFooterContainer(this._elementRef.nativeElement,t),this._footerRowDefs.forEach(i=>i.resetStickyChanged());}updateStickyColumnStyles(){let e=this._getRenderedRows(this._headerRowOutlet),t=this._getRenderedRows(this._rowOutlet),i=this._getRenderedRows(this._footerRowOutlet);(this._isNativeHtmlTable&&!this.fixedLayout||this._stickyColumnStylesNeedReset)&&(this._stickyStyler.clearStickyPositioning([...e,...t,...i],["left","right"]),this._stickyColumnStylesNeedReset=false),e.forEach((a,o)=>{this._addStickyColumnStyles([a],this._headerRowDefs[o]);}),this._rowDefs.forEach(a=>{let o=[];for(let c=0;c<t.length;c++)this._renderRows[c].rowDef===a&&o.push(t[c]);this._addStickyColumnStyles(o,a);}),i.forEach((a,o)=>{this._addStickyColumnStyles([a],this._footerRowDefs[o]);}),Array.from(this._columnDefsByName.values()).forEach(a=>a.resetStickyChanged());}stickyColumnsUpdated(e){this._positionListener?.stickyColumnsUpdated(e);}stickyEndColumnsUpdated(e){this._positionListener?.stickyEndColumnsUpdated(e);}stickyHeaderRowsUpdated(e){this._headerRowStickyUpdates.next(e),this._positionListener?.stickyHeaderRowsUpdated(e);}stickyFooterRowsUpdated(e){this._footerRowStickyUpdates.next(e),this._positionListener?.stickyFooterRowsUpdated(e);}_outletAssigned(){!this._hasAllOutlets&&this._rowOutlet&&this._headerRowOutlet&&this._footerRowOutlet&&this._noDataRowOutlet&&(this._hasAllOutlets=true,this._canRender()&&this._render());}_canRender(){return this._hasAllOutlets&&this._hasInitialized}_render(){this._cacheRowDefs(),this._cacheColumnDefs(),!this._headerRowDefs.length&&!this._footerRowDefs.length&&this._rowDefs.length;let t=this._renderUpdatedColumns()||this._headerRowDefChanged||this._footerRowDefChanged;this._stickyColumnStylesNeedReset=this._stickyColumnStylesNeedReset||t,this._forceRecalculateCellWidths=t,this._headerRowDefChanged&&(this._forceRenderHeaderRows(),this._headerRowDefChanged=false),this._footerRowDefChanged&&(this._forceRenderFooterRows(),this._footerRowDefChanged=false),this.dataSource&&this._rowDefs.length>0&&!this._renderChangeSubscription?this._observeRenderChanges():this._stickyColumnStylesNeedReset&&this.updateStickyColumnStyles(),this._checkStickyStates();}_getAllRenderRows(){if(!Array.isArray(this._data)||!this._renderedRange)return [];let e=[],t=Math.min(this._data.length,this._renderedRange.end),i=this._cachedRenderRowsMap;this._cachedRenderRowsMap=new Map;for(let a=this._renderedRange.start;a<t;a++){let o=this._data[a],c=this._getRenderRowsForData(o,a,i.get(o));this._cachedRenderRowsMap.has(o)||this._cachedRenderRowsMap.set(o,new WeakMap);for(let y=0;y<c.length;y++){let F=c[y],H=this._cachedRenderRowsMap.get(F.data);H.has(F.rowDef)?H.get(F.rowDef).push(F):H.set(F.rowDef,[F]),e.push(F);}}return e}_getRenderRowsForData(e,t,i){return this._getRowDefs(e,t).map(o=>{let c=i&&i.has(o)?i.get(o):[];if(c.length){let y=c.shift();return y.dataIndex=t,y}else return {data:e,rowDef:o,dataIndex:t}})}_cacheColumnDefs(){this._columnDefsByName.clear(),Pn(this._getOwnDefs(this._contentColumnDefs),this._customColumnDefs).forEach(t=>{this._columnDefsByName.has(t.name),this._columnDefsByName.set(t.name,t);});}_cacheRowDefs(){this._headerRowDefs=Pn(this._getOwnDefs(this._contentHeaderRowDefs),this._customHeaderRowDefs),this._footerRowDefs=Pn(this._getOwnDefs(this._contentFooterRowDefs),this._customFooterRowDefs),this._rowDefs=Pn(this._getOwnDefs(this._contentRowDefs),this._customRowDefs);let e=this._rowDefs.filter(t=>!t.when);this._defaultRowDef=e[0];}_renderUpdatedColumns(){let e=(o,c)=>{let y=!!c.getColumnsDiff();return o||y},t=this._rowDefs.reduce(e,false);t&&this._forceRenderDataRows();let i=this._headerRowDefs.reduce(e,false);i&&this._forceRenderHeaderRows();let a=this._footerRowDefs.reduce(e,false);return a&&this._forceRenderFooterRows(),t||i||a}_switchDataSource(e){this._data=[],Qm$1(this.dataSource)&&this.dataSource.disconnect(this),this._renderChangeSubscription&&(this._renderChangeSubscription.unsubscribe(),this._renderChangeSubscription=null),e||(this._dataDiffer&&this._dataDiffer.diff([]),this._rowOutlet&&this._rowOutlet.viewContainer.clear()),this._dataSource=e;}_observeRenderChanges(){if(!this.dataSource)return;let e;Qm$1(this.dataSource)?e=this.dataSource.connect(this):dg(this.dataSource)?e=this.dataSource:Array.isArray(this.dataSource)&&(e=rg(this.dataSource)),this._renderChangeSubscription=wg([e,this.viewChange]).pipe(Bl$1(this._onDestroy)).subscribe(([t,i])=>{this._data=t||[],this._renderedRange=i,this._dataStream.next(t),this.renderRows();});}_forceRenderHeaderRows(){this._headerRowOutlet.viewContainer.length>0&&this._headerRowOutlet.viewContainer.clear(),this._headerRowDefs.forEach((e,t)=>this._renderRow(this._headerRowOutlet,e,t)),this.updateStickyHeaderRowStyles();}_forceRenderFooterRows(){this._footerRowOutlet.viewContainer.length>0&&this._footerRowOutlet.viewContainer.clear(),this._footerRowDefs.forEach((e,t)=>this._renderRow(this._footerRowOutlet,e,t)),this.updateStickyFooterRowStyles();}_addStickyColumnStyles(e,t){let i=Array.from(t?.columns||[]).map(c=>{let y=this._columnDefsByName.get(c);return y}),a=i.map(c=>c.sticky),o=i.map(c=>c.stickyEnd);this._stickyStyler.updateStickyColumns(e,a,o,!this.fixedLayout||this._forceRecalculateCellWidths);}_getRenderedRows(e){let t=[];for(let i=0;i<e.viewContainer.length;i++){let a=e.viewContainer.get(i);t.push(a.rootNodes[0]);}return t}_getRowDefs(e,t){if(this._rowDefs.length===1)return [this._rowDefs[0]];let i=[];if(this.multiTemplateDataRows)i=this._rowDefs.filter(a=>!a.when||a.when(t,e));else {let a=this._rowDefs.find(o=>o.when&&o.when(t,e))||this._defaultRowDef;a&&i.push(a);}return i.length,i}_getEmbeddedViewArgs(e,t){let i=e.rowDef,a={$implicit:e.data};return {templateRef:i.template,context:a,index:t}}_renderRow(e,t,i,a={}){let o=e.viewContainer.createEmbeddedView(t.template,a,i);return this._renderCellTemplateForItem(t,a),o}_renderCellTemplateForItem(e,t){for(let i of this._getCellTemplates(e))Yt.mostRecentCellOutlet&&Yt.mostRecentCellOutlet._viewContainer.createEmbeddedView(i,t);this._changeDetectorRef.markForCheck();}_updateRowIndexContext(){let e=this._rowOutlet.viewContainer;for(let t=0,i=e.length;t<i;t++){let o=e.get(t).context;o.count=i,o.first=t===0,o.last=t===i-1,o.even=t%2===0,o.odd=!o.even,this.multiTemplateDataRows?(o.dataIndex=this._renderRows[t].dataIndex,o.renderIndex=t):o.index=this._renderRows[t].dataIndex;}}_getCellTemplates(e){return !e||!e.columns?[]:Array.from(e.columns,t=>{let i=this._columnDefsByName.get(t);return e.extractCellTemplate(i)})}_forceRenderDataRows(){this._dataDiffer.diff([]),this._rowOutlet.viewContainer.clear(),this.renderRows();}_checkStickyStates(){let e=(t,i)=>t||i.hasStickyChanged();this._headerRowDefs.reduce(e,false)&&this.updateStickyHeaderRowStyles(),this._footerRowDefs.reduce(e,false)&&this.updateStickyFooterRowStyles(),Array.from(this._columnDefsByName.values()).reduce(e,false)&&(this._stickyColumnStylesNeedReset=true,this.updateStickyColumnStyles());}_setupStickyStyler(){let e=this._dir?this._dir.value:"ltr",t=this._injector;this._stickyStyler=new qa(this._isNativeHtmlTable,this.stickyCssClass,this._platform.isBrowser,this.needsPositionStickyOnElement,e,this,t),(this._dir?this._dir.change:rg()).pipe(Bl$1(this._onDestroy)).subscribe(i=>{this._stickyStyler.direction=i,this.updateStickyColumnStyles();});}_setupVirtualScrolling(e){let t=typeof requestAnimationFrame<"u"?lg:cg;this.viewChange.next({start:0,end:0}),e.renderedRangeStream.pipe(Og(0,t),Bl$1(this._onDestroy)).subscribe(this.viewChange),e.attach({dataStream:this._dataStream,measureRangeSize:(i,a)=>this._measureRangeSize(i,a)}),wg([e.renderedContentOffset,this._headerRowStickyUpdates]).pipe(Bl$1(this._onDestroy)).subscribe(([i,a])=>{if(!(!a.sizes||!a.offsets||!a.elements))for(let o=0;o<a.elements.length;o++){let c=a.elements[o];if(c){let y=a.offsets[o],F=i!==0?Math.max(i-y,y):-y;for(let H of c)H.style.top=`${-F}px`;}}}),wg([e.renderedContentOffset,this._footerRowStickyUpdates]).pipe(Bl$1(this._onDestroy)).subscribe(([i,a])=>{if(!(!a.sizes||!a.offsets||!a.elements))for(let o=0;o<a.elements.length;o++){let c=a.elements[o];if(c)for(let y of c)y.style.bottom=`${i+a.offsets[o]}px`;}});}_getOwnDefs(e){return e.filter(t=>!t._table||t._table===this)}_updateNoDataRow(){let e=this._customNoDataRow||this._noDataRow;if(!e)return;let t=this._rowOutlet.viewContainer.length===0;if(t===this._isShowingNoDataRow)return;let i=this._noDataRowOutlet.viewContainer;if(t){let a=i.createEmbeddedView(e.templateRef),o=a.rootNodes[0];if(a.rootNodes.length===1&&o?.nodeType===this._document.ELEMENT_NODE){o.setAttribute("role","row"),o.classList.add(...e._contentClassNames);let c=o.querySelectorAll(e._cellSelector);for(let y=0;y<c.length;y++)c[y].classList.add(...e._cellClassNames);}}else i.clear();this._isShowingNoDataRow=t,this._changeDetectorRef.markForCheck();}_measureRangeSize(e,t){if(e.start>=e.end||t!=="vertical")return 0;let i=this.viewChange.value,a=this._rowOutlet.viewContainer;e.start<i.start||e.end>i.end;let o=e.start-i.start,c=e.end-e.start,y,F;for(let _e=0;_e<c;_e++){let Me=a.get(_e+o);if(Me&&Me.rootNodes.length){y=F=Me.rootNodes[0];break}}for(let _e=c-1;_e>-1;_e--){let Me=a.get(_e+o);if(Me&&Me.rootNodes.length){F=Me.rootNodes[Me.rootNodes.length-1];break}}let H=y?.getBoundingClientRect?.(),De=F?.getBoundingClientRect?.();return H&&De?De.bottom-H.top:0}_virtualScrollEnabled(){return !this._disableVirtualScrolling&&this._virtualScrollViewport!=null}static \u0275fac=function(t){return new(t||n)};static \u0275cmp=TE({type:n,selectors:[["cdk-table"],["table","cdk-table",""]],contentQueries:function(t,i,a){if(t&1&&ih$1(a,rl,5)(a,fi,5)(a,Bn,5)(a,Bi,5)(a,Ya,5),t&2){let o;vD(o=ID())&&(i._noDataRow=o.first),vD(o=ID())&&(i._contentColumnDefs=o),vD(o=ID())&&(i._contentRowDefs=o),vD(o=ID())&&(i._contentHeaderRowDefs=o),vD(o=ID())&&(i._contentFooterRowDefs=o);}},hostAttrs:[1,"cdk-table"],hostVars:2,hostBindings:function(t,i){t&2&&fh$1("cdk-table-fixed-layout",i.fixedLayout);},inputs:{trackBy:"trackBy",dataSource:"dataSource",multiTemplateDataRows:[2,"multiTemplateDataRows","multiTemplateDataRows",j0],fixedLayout:[2,"fixedLayout","fixedLayout",j0],recycleRows:[2,"recycleRows","recycleRows",j0]},outputs:{contentChanged:"contentChanged"},exportAs:["cdkTable"],features:[JD([{provide:_t,useExisting:n},{provide:Ni,useValue:null}])],ngContentSelectors:Fm,decls:5,vars:2,consts:[["role","rowgroup"],["headerRowOutlet",""],["rowOutlet",""],["noDataRowOutlet",""],["footerRowOutlet",""]],template:function(t,i){t&1&&(gD(Rm),mD(0),mD(1,1),QE(2,Om,1,0),QE(3,Pm,7,0)(4,Vm,4,0)),t&2&&(Wv(2),ZE(i._isServer?2:-1),Wv(),ZE(i._isNativeHtmlTable?3:4));},dependencies:[Za,Xa,er,Ja],styles:[`.cdk-table-fixed-layout {
  table-layout: fixed;
}
`],encapsulation:2,changeDetection:1})}return n})();function Pn(n,r){return n.concat(Array.from(r))}function tl(n,r){let e=r.toUpperCase(),t=n.viewContainer.element.nativeElement;for(;t;){let i=t.nodeType===1?t.nodeName:null;if(i===e)return t;if(i==="TABLE")break;t=t.parentNode;}return null}var ol=(()=>{class n{static \u0275fac=function(t){return new(t||n)};static \u0275mod=bE({type:n});static \u0275inj=cu$1({imports:[ui$1]})}return n})();var Nm=[[["caption"]],[["colgroup"],["col"]],"*"],Bm=["caption","colgroup, col","*"];function zm(n,r){n&1&&mD(0,2);}function Hm(n,r){n&1&&(yi(0,"thead",0),Xp(1,1),jc$1(),yi(2,"tbody",2),Xp(3,3)(4,4),jc$1(),yi(5,"tfoot",0),Xp(6,5),jc$1());}function jm(n,r){n&1&&Xp(0,1)(1,3)(2,4)(3,5);}var Py=(()=>{class n extends tr{stickyCssClass="mat-mdc-table-sticky";needsPositionStickyOnElement=false;static \u0275fac=(()=>{let e;return function(i){return (e||(e=fy(n)))(i||n)}})();static \u0275cmp=TE({type:n,selectors:[["mat-table"],["table","mat-table",""]],hostAttrs:[1,"mat-mdc-table","mdc-data-table__table"],hostVars:2,hostBindings:function(t,i){t&2&&fh$1("mat-table-fixed-layout",i.fixedLayout);},exportAs:["matTable"],features:[JD([{provide:tr,useExisting:n},{provide:_t,useExisting:n},{provide:Ni,useValue:null}]),Hp$1],ngContentSelectors:Bm,decls:5,vars:2,consts:[["role","rowgroup"],["headerRowOutlet",""],["role","rowgroup",1,"mdc-data-table__content"],["rowOutlet",""],["noDataRowOutlet",""],["footerRowOutlet",""]],template:function(t,i){t&1&&(gD(Nm),mD(0),mD(1,1),QE(2,zm,1,0),QE(3,Hm,7,0)(4,jm,4,0)),t&2&&(Wv(2),ZE(i._isServer?2:-1),Wv(),ZE(i._isNativeHtmlTable?3:4));},dependencies:[Za,Xa,er,Ja],styles:[`.mat-mdc-table-sticky {
  position: sticky !important;
}

mat-table {
  display: block;
}

mat-header-row {
  min-height: var(--mat-table-header-container-height, 56px);
}

mat-row {
  min-height: var(--mat-table-row-item-container-height, 52px);
}

mat-footer-row {
  min-height: var(--mat-table-footer-container-height, 52px);
}

mat-row, mat-header-row, mat-footer-row {
  display: flex;
  border-width: 0;
  border-bottom-width: 1px;
  border-style: solid;
  align-items: center;
  box-sizing: border-box;
}

mat-cell:first-of-type, mat-header-cell:first-of-type, mat-footer-cell:first-of-type {
  padding-left: 24px;
}
[dir=rtl] mat-cell:first-of-type:not(:only-of-type), [dir=rtl] mat-header-cell:first-of-type:not(:only-of-type), [dir=rtl] mat-footer-cell:first-of-type:not(:only-of-type) {
  padding-left: 0;
  padding-right: 24px;
}
mat-cell:last-of-type, mat-header-cell:last-of-type, mat-footer-cell:last-of-type {
  padding-right: 24px;
}
[dir=rtl] mat-cell:last-of-type:not(:only-of-type), [dir=rtl] mat-header-cell:last-of-type:not(:only-of-type), [dir=rtl] mat-footer-cell:last-of-type:not(:only-of-type) {
  padding-right: 0;
  padding-left: 24px;
}

mat-cell, mat-header-cell, mat-footer-cell {
  flex: 1;
  display: flex;
  align-items: center;
  overflow: hidden;
  word-wrap: break-word;
  min-height: inherit;
}

.mat-mdc-table {
  min-width: 100%;
  border: 0;
  border-spacing: 0;
  table-layout: auto;
  white-space: normal;
  background-color: var(--mat-table-background-color, var(--mat-sys-surface));
}

.mat-table-fixed-layout {
  table-layout: fixed;
}

.mdc-data-table__cell {
  box-sizing: border-box;
  overflow: hidden;
  text-align: start;
  text-overflow: ellipsis;
}

.mdc-data-table__cell,
.mdc-data-table__header-cell {
  padding: 0 16px;
}

.mat-mdc-header-row {
  -moz-osx-font-smoothing: grayscale;
  -webkit-font-smoothing: antialiased;
  height: var(--mat-table-header-container-height, 56px);
  color: var(--mat-table-header-headline-color, var(--mat-sys-on-surface, rgba(0, 0, 0, 0.87)));
  font-family: var(--mat-table-header-headline-font, var(--mat-sys-title-small-font, Roboto, sans-serif));
  line-height: var(--mat-table-header-headline-line-height, var(--mat-sys-title-small-line-height));
  font-size: var(--mat-table-header-headline-size, var(--mat-sys-title-small-size, 14px));
  font-weight: var(--mat-table-header-headline-weight, var(--mat-sys-title-small-weight, 500));
}

.mat-mdc-row {
  height: var(--mat-table-row-item-container-height, 52px);
  color: var(--mat-table-row-item-label-text-color, var(--mat-sys-on-surface, rgba(0, 0, 0, 0.87)));
}

.mat-mdc-row,
.mdc-data-table__content {
  -moz-osx-font-smoothing: grayscale;
  -webkit-font-smoothing: antialiased;
  font-family: var(--mat-table-row-item-label-text-font, var(--mat-sys-body-medium-font, Roboto, sans-serif));
  line-height: var(--mat-table-row-item-label-text-line-height, var(--mat-sys-body-medium-line-height));
  font-size: var(--mat-table-row-item-label-text-size, var(--mat-sys-body-medium-size, 14px));
  font-weight: var(--mat-table-row-item-label-text-weight, var(--mat-sys-body-medium-weight));
}

.mat-mdc-footer-row {
  -moz-osx-font-smoothing: grayscale;
  -webkit-font-smoothing: antialiased;
  height: var(--mat-table-footer-container-height, 52px);
  color: var(--mat-table-row-item-label-text-color, var(--mat-sys-on-surface, rgba(0, 0, 0, 0.87)));
  font-family: var(--mat-table-footer-supporting-text-font, var(--mat-sys-body-medium-font, Roboto, sans-serif));
  line-height: var(--mat-table-footer-supporting-text-line-height, var(--mat-sys-body-medium-line-height));
  font-size: var(--mat-table-footer-supporting-text-size, var(--mat-sys-body-medium-size, 14px));
  font-weight: var(--mat-table-footer-supporting-text-weight, var(--mat-sys-body-medium-weight));
  letter-spacing: var(--mat-table-footer-supporting-text-tracking, var(--mat-sys-body-medium-tracking));
}

.mat-mdc-header-cell {
  border-bottom-color: var(--mat-table-row-item-outline-color, var(--mat-sys-outline, rgba(0, 0, 0, 0.12)));
  border-bottom-width: var(--mat-table-row-item-outline-width, 1px);
  border-bottom-style: solid;
  letter-spacing: var(--mat-table-header-headline-tracking, var(--mat-sys-title-small-tracking));
  font-weight: inherit;
  line-height: inherit;
  box-sizing: border-box;
  text-overflow: ellipsis;
  overflow: hidden;
  outline: none;
  text-align: start;
}
.mdc-data-table__row:last-child > .mat-mdc-header-cell {
  border-bottom: none;
}

.mat-mdc-cell {
  border-bottom-color: var(--mat-table-row-item-outline-color, var(--mat-sys-outline, rgba(0, 0, 0, 0.12)));
  border-bottom-width: var(--mat-table-row-item-outline-width, 1px);
  border-bottom-style: solid;
  letter-spacing: var(--mat-table-row-item-label-text-tracking, var(--mat-sys-body-medium-tracking));
  line-height: inherit;
}
.mdc-data-table__row:last-child > .mat-mdc-cell {
  border-bottom: none;
}

.mat-mdc-footer-cell {
  letter-spacing: var(--mat-table-row-item-label-text-tracking, var(--mat-sys-body-medium-tracking));
}

mat-row.mat-mdc-row,
mat-header-row.mat-mdc-header-row,
mat-footer-row.mat-mdc-footer-row {
  border-bottom: none;
}

.mat-mdc-table tbody,
.mat-mdc-table tfoot,
.mat-mdc-table thead,
.mat-mdc-cell,
.mat-mdc-footer-cell,
.mat-mdc-header-row,
.mat-mdc-row,
.mat-mdc-footer-row,
.mat-mdc-table .mat-mdc-header-cell {
  background: inherit;
}

.mat-mdc-table mat-header-row.mat-mdc-header-row,
.mat-mdc-table mat-row.mat-mdc-row,
.mat-mdc-table mat-footer-row.mat-mdc-footer-cell {
  height: unset;
}

mat-header-cell.mat-mdc-header-cell,
mat-cell.mat-mdc-cell,
mat-footer-cell.mat-mdc-footer-cell {
  align-self: stretch;
}
`],encapsulation:2,changeDetection:1})}return n})(),Vy=(()=>{class n extends Ln{static \u0275fac=(()=>{let e;return function(i){return (e||(e=fy(n)))(i||n)}})();static \u0275dir=SE({type:n,selectors:[["","matCellDef",""]],features:[JD([{provide:Ln,useExisting:n}]),Hp$1]})}return n})(),Ly=(()=>{class n extends Nn{static \u0275fac=(()=>{let e;return function(i){return (e||(e=fy(n)))(i||n)}})();static \u0275dir=SE({type:n,selectors:[["","matHeaderCellDef",""]],features:[JD([{provide:Nn,useExisting:n}]),Hp$1]})}return n})();var Ny=(()=>{class n extends fi{get name(){return this._name}set name(e){this._setNameInput(e);}_updateColumnCssClassName(){super._updateColumnCssClassName(),this._columnCssClassName.push(`mat-column-${this.cssClassFriendlyName}`);}static \u0275fac=(()=>{let e;return function(i){return (e||(e=fy(n)))(i||n)}})();static \u0275dir=SE({type:n,selectors:[["","matColumnDef",""]],inputs:{name:[0,"matColumnDef","name"]},features:[JD([{provide:fi,useExisting:n}]),Hp$1]})}return n})(),By=(()=>{class n extends nl{static \u0275fac=(()=>{let e;return function(i){return (e||(e=fy(n)))(i||n)}})();static \u0275dir=SE({type:n,selectors:[["mat-header-cell"],["th","mat-header-cell",""]],hostAttrs:["role","columnheader",1,"mat-mdc-header-cell","mdc-data-table__header-cell"],features:[Hp$1]})}return n})();var zy=(()=>{class n extends al{static \u0275fac=(()=>{let e;return function(i){return (e||(e=fy(n)))(i||n)}})();static \u0275dir=SE({type:n,selectors:[["mat-cell"],["td","mat-cell",""]],hostAttrs:[1,"mat-mdc-cell","mdc-data-table__cell"],features:[Hp$1]})}return n})();var Hy=(()=>{class n extends Bi{static \u0275fac=(()=>{let e;return function(i){return (e||(e=fy(n)))(i||n)}})();static \u0275dir=SE({type:n,selectors:[["","matHeaderRowDef",""]],inputs:{columns:[0,"matHeaderRowDef","columns"],sticky:[2,"matHeaderRowDefSticky","sticky",j0]},features:[JD([{provide:Bi,useExisting:n}]),Hp$1]})}return n})();var jy=(()=>{class n extends Bn{static \u0275fac=(()=>{let e;return function(i){return (e||(e=fy(n)))(i||n)}})();static \u0275dir=SE({type:n,selectors:[["","matRowDef",""]],inputs:{columns:[0,"matRowDefColumns","columns"],when:[0,"matRowDefWhen","when"]},features:[JD([{provide:Bn,useExisting:n}]),Hp$1]})}return n})(),Gy=(()=>{class n extends Ka{static \u0275fac=(()=>{let e;return function(i){return (e||(e=fy(n)))(i||n)}})();static \u0275cmp=TE({type:n,selectors:[["mat-header-row"],["tr","mat-header-row",""]],hostAttrs:["role","row",1,"mat-mdc-header-row","mdc-data-table__header-row"],exportAs:["matHeaderRow"],features:[JD([{provide:Ka,useExisting:n}]),Hp$1],decls:1,vars:0,consts:[["cdkCellOutlet",""]],template:function(t,i){t&1&&Xp(0,0);},dependencies:[Yt],encapsulation:2,changeDetection:1})}return n})();var Uy=(()=>{class n extends $a{static \u0275fac=(()=>{let e;return function(i){return (e||(e=fy(n)))(i||n)}})();static \u0275cmp=TE({type:n,selectors:[["mat-row"],["tr","mat-row",""]],hostAttrs:["role","row",1,"mat-mdc-row","mdc-data-table__row"],exportAs:["matRow"],features:[JD([{provide:$a,useExisting:n}]),Hp$1],decls:1,vars:0,consts:[["cdkCellOutlet",""]],template:function(t,i){t&1&&Xp(0,0);},dependencies:[Yt],encapsulation:2,changeDetection:1})}return n})();var ir=(()=>{class n{static \u0275fac=function(t){return new(t||n)};static \u0275mod=bE({type:n});static \u0275inj=cu$1({imports:[ol,jo$1]})}return n})();var lr=["*"];function Gm(n,r){n&1&&mD(0);}var Um=["tabListContainer"],Wm=["tabList"],qm=["tabListInner"],Qm=["nextPaginator"],Ym=["previousPaginator"],Km=["content"];function $m(n,r){}var Xm=["tabBodyWrapper"],Zm=["tabHeader"];function Jm(n,r){}function eh(n,r){if(n&1&&$p(0,Jm,0,0,"ng-template",12),n&2){let e=pD().$implicit;Zp("cdkPortalOutlet",e.templateLabel);}}function th(n,r){if(n&1&&UD(0),n&2){let e=pD().$implicit;Ih$1(e.textLabel);}}function ih(n,r){if(n&1){let e=iD();yi(0,"div",7,2),nh$1("click",function(){let i=ku(e),a=i.$implicit,o=i.$index,c=pD(),y=DD(1);return Ou(c._handleClick(a,y,o))})("cdkFocusChange",function(i){let a=ku(e).$index,o=pD();return Ou(o._tabFocusChanged(i,a))}),Yp(2,"span",8)(3,"div",9),yi(4,"span",10)(5,"span",11),QE(6,eh,1,1,null,12)(7,th,1,1),jc$1()()();}if(n&2){let e=r.$implicit,t=r.$index,i=DD(1),a=pD();RD(e.labelClass),fh$1("mdc-tab--active",a.selectedIndex===t),Zp("id",a._getTabLabelId(e,t))("disabled",e.disabled)("fitInkBarToContent",a.fitInkBarToContent),Qp("tabIndex",a._getTabIndex(t))("aria-posinset",t+1)("aria-setsize",a._tabs.length)("aria-controls",a._getTabContentId(t))("aria-selected",a.selectedIndex===t)("aria-label",e.ariaLabel||null)("aria-labelledby",!e.ariaLabel&&e.ariaLabelledby?e.ariaLabelledby:null),Wv(3),Zp("matRippleTrigger",i)("matRippleDisabled",e.disabled||a.disableRipple),Wv(3),ZE(e.templateLabel?6:7);}}function nh(n,r){n&1&&mD(0);}function ah(n,r){if(n&1){let e=iD();yi(0,"mat-tab-body",13),nh$1("_onCentered",function(){ku(e);let i=pD();return Ou(i._removeTabBodyWrapperHeight())})("_onCentering",function(i){ku(e);let a=pD();return Ou(a._setTabBodyWrapperHeight(i))})("_beforeCentering",function(i){ku(e);let a=pD();return Ou(a._bodyCentered(i))}),jc$1();}if(n&2){let e=r.$implicit,t=r.$index,i=pD();RD(e.bodyClass),Zp("id",i._getTabContentId(t))("content",e.content)("position",e.position)("animationDuration",i._bodyAnimationDuration)("preserveContent",i.preserveContent),Qp("tabindex",i.contentTabIndex!=null&&i.selectedIndex===t?i.contentTabIndex:null)("aria-labelledby",i._getTabLabelId(e,t))("aria-hidden",i.selectedIndex!==t);}}var rh=new x("MatTabContent"),oh=(()=>{class n{template=T(hr$1);static \u0275fac=function(t){return new(t||n)};static \u0275dir=SE({type:n,selectors:[["","matTabContent",""]],features:[JD([{provide:rh,useExisting:n}])]})}return n})(),sh=new x("MatTabLabel"),cl=new x("MAT_TAB"),lh=(()=>{class n extends Pu{_closestTab=T(cl,{optional:true});static \u0275fac=(()=>{let e;return function(i){return (e||(e=fy(n)))(i||n)}})();static \u0275dir=SE({type:n,selectors:[["","mat-tab-label",""],["","matTabLabel",""]],features:[JD([{provide:sh,useExisting:n}]),Hp$1]})}return n})(),ml=new x("MAT_TAB_GROUP"),dh=(()=>{class n{_viewContainerRef=T(Ai$1);_closestTabGroup=T(ml,{optional:true});disabled=false;get templateLabel(){return this._templateLabel}set templateLabel(e){this._setTemplateLabelInput(e);}_templateLabel;_explicitContent=void 0;_implicitContent;textLabel="";ariaLabel;ariaLabelledby;labelClass;bodyClass;id=null;_contentPortal=null;get content(){return this._contentPortal}_stateChanges=new ne;position=null;origin=null;isActive=false;constructor(){T(W$1).load(bn$1);}ngOnChanges(e){(e.hasOwnProperty("textLabel")||e.hasOwnProperty("disabled"))&&this._stateChanges.next();}ngOnDestroy(){this._stateChanges.complete();}ngOnInit(){this._contentPortal=new at$1(this._explicitContent||this._implicitContent,this._viewContainerRef);}_setTemplateLabelInput(e){e&&e._closestTab===this&&(this._templateLabel=e);}static \u0275fac=function(t){return new(t||n)};static \u0275cmp=TE({type:n,selectors:[["mat-tab"]],contentQueries:function(t,i,a){if(t&1&&ih$1(a,lh,5)(a,oh,7,hr$1),t&2){let o;vD(o=ID())&&(i.templateLabel=o.first),vD(o=ID())&&(i._explicitContent=o.first);}},viewQuery:function(t,i){if(t&1&&sh$1(hr$1,7),t&2){let a;vD(a=ID())&&(i._implicitContent=a.first);}},hostAttrs:["hidden",""],hostVars:1,hostBindings:function(t,i){t&2&&Qp("id",null);},inputs:{disabled:[2,"disabled","disabled",j0],textLabel:[0,"label","textLabel"],ariaLabel:[0,"aria-label","ariaLabel"],ariaLabelledby:[0,"aria-labelledby","ariaLabelledby"],labelClass:"labelClass",bodyClass:"bodyClass",id:"id"},exportAs:["matTab"],features:[JD([{provide:cl,useExisting:n}]),zm$1],ngContentSelectors:lr,decls:1,vars:0,template:function(t,i){t&1&&(gD(),Up(0,Gm,1,0,"ng-template"));},encapsulation:2,changeDetection:1})}return n})(),nr="mdc-tab-indicator--active",sl="mdc-tab-indicator--no-transition",rr=class{_items;_currentItem;constructor(r){this._items=r;}hide(){this._items.forEach(r=>r.deactivateInkBar()),this._currentItem=void 0;}alignToElement(r){let e=this._items.find(i=>i.elementRef.nativeElement===r),t=this._currentItem;if(e!==t&&(t?.deactivateInkBar(),e)){let i=t?.elementRef.nativeElement.getBoundingClientRect?.();e.activateInkBar(i),this._currentItem=e;}}},ch=(()=>{class n{_elementRef=T(Dr$1);_inkBarElement=null;_inkBarContentElement=null;_fitToContent=false;get fitInkBarToContent(){return this._fitToContent}set fitInkBarToContent(e){this._fitToContent!==e&&(this._fitToContent=e,this._inkBarElement&&this._appendInkBarElement());}activateInkBar(e){let t=this._elementRef.nativeElement;if(!e||!t.getBoundingClientRect||!this._inkBarContentElement){t.classList.add(nr);return}let i=t.getBoundingClientRect(),a=e.width/i.width,o=e.left-i.left;t.classList.add(sl),this._inkBarContentElement.style.setProperty("transform",`translateX(${o}px) scaleX(${a})`),t.getBoundingClientRect(),t.classList.remove(sl),t.classList.add(nr),this._inkBarContentElement.style.setProperty("transform","");}deactivateInkBar(){this._elementRef.nativeElement.classList.remove(nr);}ngOnInit(){this._createInkBarElement();}ngOnDestroy(){this._inkBarElement?.remove(),this._inkBarElement=this._inkBarContentElement=null;}_createInkBarElement(){let e=this._elementRef.nativeElement.ownerDocument||document,t=this._inkBarElement=e.createElement("span"),i=this._inkBarContentElement=e.createElement("span");t.className="mdc-tab-indicator",i.className="mdc-tab-indicator__content mdc-tab-indicator__content--underline",t.appendChild(this._inkBarContentElement),this._appendInkBarElement();}_appendInkBarElement(){this._inkBarElement;let e=this._fitToContent?this._elementRef.nativeElement.querySelector(".mdc-tab__content"):this._elementRef.nativeElement;e.appendChild(this._inkBarElement);}static \u0275fac=function(t){return new(t||n)};static \u0275dir=SE({type:n,inputs:{fitInkBarToContent:[2,"fitInkBarToContent","fitInkBarToContent",j0]}})}return n})();var hl=(()=>{class n extends ch{elementRef=T(Dr$1);disabled=false;focus(){this.elementRef.nativeElement.focus();}getOffsetLeft(){return this.elementRef.nativeElement.offsetLeft}getOffsetWidth(){return this.elementRef.nativeElement.offsetWidth}static \u0275fac=(()=>{let e;return function(i){return (e||(e=fy(n)))(i||n)}})();static \u0275dir=SE({type:n,selectors:[["","matTabLabelWrapper",""]],hostVars:3,hostBindings:function(t,i){t&2&&(Qp("aria-disabled",!!i.disabled),fh$1("mat-mdc-tab-disabled",i.disabled));},inputs:{disabled:[2,"disabled","disabled",j0]},features:[Hp$1]})}return n})(),ll={passive:true},mh=650,hh=100;function ar(n){let r=n+"";return /^[0-9]+(?:\.[0-9]+)?$/.test(r)?`${n}ms`:/^[0-9]+(?:\.[0-9]+)?(?:ms|s)$/.test(r)?r:""}var ph=(()=>{class n{_elementRef=T(Dr$1);_changeDetectorRef=T(L0);_viewportRuler=T(he);_dir=T(Er$2,{optional:true});_ngZone=T(Se);_platform=T(D);_sharedResizeObserver=T(Sn);_injector=T(ge);_renderer=T(MI);_animationsDisabled=B();_eventCleanups;_scrollDistance=0;_selectedIndexChanged=false;_destroyed=new ne;_showPaginationControls=false;_disableScrollAfter=true;_disableScrollBefore=true;_tabLabelCount;_scrollDistanceChanged=false;_keyManager;_currentTextContent;_stopScrolling=new ne;disablePagination=false;get selectedIndex(){return this._selectedIndex}set selectedIndex(e){let t=isNaN(e)?0:e;this._selectedIndex!=t&&(this._selectedIndexChanged=true,this._selectedIndex=t,this._keyManager&&this._keyManager.updateActiveItem(t));}_selectedIndex=0;selectFocusedIndex=new $e;indexFocused=new $e;constructor(){this._eventCleanups=this._ngZone.runOutsideAngular(()=>[this._renderer.listen(this._elementRef.nativeElement,"mouseleave",()=>this._stopInterval())]);}ngAfterViewInit(){this._eventCleanups.push(this._renderer.listen(this._previousPaginator.nativeElement,"touchstart",()=>this._handlePaginatorPress("before"),ll),this._renderer.listen(this._nextPaginator.nativeElement,"touchstart",()=>this._handlePaginatorPress("after"),ll));}ngAfterContentInit(){let e=this._dir?this._dir.change:rg("ltr"),t=this._sharedResizeObserver.observe(this._elementRef.nativeElement).pipe(wl$1(32),Bl$1(this._destroyed)),i=this._viewportRuler.change(150).pipe(Bl$1(this._destroyed)),a=()=>{this.updatePagination(),this._alignInkBarToSelectedTab();};this._keyManager=new ni(this._items).withHorizontalOrientation(this._getLayoutDirection()).withHomeAndEnd().withWrap().skipPredicate(()=>false),this._keyManager.updateActiveItem(Math.max(this._selectedIndex,0)),Dv(a,{injector:this._injector}),kg(e,i,t,this._items.changes,this._itemsResized()).pipe(Bl$1(this._destroyed)).subscribe(()=>{this._ngZone.run(()=>{Promise.resolve().then(()=>{this._scrollDistance=Math.max(0,Math.min(this._getMaxScrollDistance(),this._scrollDistance)),a();});}),this._keyManager?.withHorizontalOrientation(this._getLayoutDirection());}),this._keyManager.change.subscribe(o=>{this.indexFocused.emit(o),this._setTabFocus(o);});}_itemsResized(){return typeof ResizeObserver!="function"?Dt:this._items.changes.pipe(Hl$1(this._items),Cl$1(e=>new _(t=>this._ngZone.runOutsideAngular(()=>{let i=new ResizeObserver(a=>t.next(a));return e.forEach(a=>i.observe(a.elementRef.nativeElement)),()=>{i.disconnect();}}))),Bg(1),en(e=>e.some(t=>t.contentRect.width>0&&t.contentRect.height>0)))}ngAfterContentChecked(){this._tabLabelCount!=this._items.length&&(this.updatePagination(),this._tabLabelCount=this._items.length,this._changeDetectorRef.markForCheck()),this._selectedIndexChanged&&(this._scrollToLabel(this._selectedIndex),this._checkScrollingControls(),this._alignInkBarToSelectedTab(),this._selectedIndexChanged=false,this._changeDetectorRef.markForCheck()),this._scrollDistanceChanged&&(this._updateTabScrollPosition(),this._scrollDistanceChanged=false,this._changeDetectorRef.markForCheck());}ngOnDestroy(){this._eventCleanups.forEach(e=>e()),this._keyManager?.destroy(),this._destroyed.next(),this._destroyed.complete(),this._stopScrolling.complete();}_handleKeydown(e){if(!$(e))switch(e.keyCode){case 13:case 32:if(this.focusIndex!==this.selectedIndex){let t=this._items.get(this.focusIndex);t&&!t.disabled&&(this.selectFocusedIndex.emit(this.focusIndex),this._itemSelected(e));}break;default:this._keyManager?.onKeydown(e);}}_onContentChanges(){let e=this._elementRef.nativeElement.textContent;e!==this._currentTextContent&&(this._currentTextContent=e||"",this._ngZone.run(()=>{this.updatePagination(),this._alignInkBarToSelectedTab(),this._changeDetectorRef.markForCheck();}));}updatePagination(){this._checkPaginationEnabled(),this._checkScrollingControls(),this._updateTabScrollPosition();}get focusIndex(){return this._keyManager?this._keyManager.activeItemIndex:0}set focusIndex(e){!this._isValidIndex(e)||this.focusIndex===e||!this._keyManager||this._keyManager.setActiveItem(e);}_isValidIndex(e){return this._items?!!this._items.toArray()[e]:true}_setTabFocus(e){if(this._showPaginationControls&&this._scrollToLabel(e),this._items&&this._items.length){this._items.toArray()[e].focus();let t=this._tabListContainer.nativeElement;this._getLayoutDirection()=="ltr"?t.scrollLeft=0:t.scrollLeft=t.scrollWidth-t.offsetWidth;}}_getLayoutDirection(){return this._dir&&this._dir.value==="rtl"?"rtl":"ltr"}_updateTabScrollPosition(){if(this.disablePagination)return;let e=this.scrollDistance,t=this._getLayoutDirection()==="ltr"?-e:e;this._tabList.nativeElement.style.transform=`translateX(${Math.round(t)}px)`,(this._platform.TRIDENT||this._platform.EDGE)&&(this._tabListContainer.nativeElement.scrollLeft=0);}get scrollDistance(){return this._scrollDistance}set scrollDistance(e){this._scrollTo(e);}_scrollHeader(e){let t=this._tabListContainer.nativeElement.offsetWidth,i=(e=="before"?-1:1)*t/3;return this._scrollTo(this._scrollDistance+i)}_handlePaginatorClick(e){this._stopInterval(),this._scrollHeader(e);}_scrollToLabel(e){if(this.disablePagination)return;let t=this._items?this._items.toArray()[e]:null;if(!t)return;let i=this._tabListContainer.nativeElement.offsetWidth,{offsetLeft:a,offsetWidth:o}=t.elementRef.nativeElement,c,y;this._getLayoutDirection()=="ltr"?(c=a,y=c+o):(y=this._tabListInner.nativeElement.offsetWidth-a,c=y-o);let F=this.scrollDistance,H=this.scrollDistance+i;c<F?this.scrollDistance-=F-c:y>H&&(this.scrollDistance+=Math.min(y-H,c-F));}_checkPaginationEnabled(){if(this.disablePagination)this._showPaginationControls=false;else {let e=this._tabListInner.nativeElement.scrollWidth,t=this._elementRef.nativeElement.offsetWidth,i=e-t>=5;i||(this.scrollDistance=0),i!==this._showPaginationControls&&(this._showPaginationControls=i,this._changeDetectorRef.markForCheck());}}_checkScrollingControls(){this.disablePagination?this._disableScrollAfter=this._disableScrollBefore=true:(this._disableScrollBefore=this.scrollDistance==0,this._disableScrollAfter=this.scrollDistance==this._getMaxScrollDistance(),this._changeDetectorRef.markForCheck());}_getMaxScrollDistance(){let e=this._tabListInner.nativeElement.scrollWidth,t=this._tabListContainer.nativeElement.offsetWidth;return e-t||0}_alignInkBarToSelectedTab(){let e=this._items&&this._items.length?this._items.toArray()[this.selectedIndex]:null,t=e?e.elementRef.nativeElement:null;t?this._inkBar.alignToElement(t):this._inkBar.hide();}_stopInterval(){this._stopScrolling.next();}_handlePaginatorPress(e,t){t&&t.button!=null&&t.button!==0||(this._stopInterval(),He(mh,hh).pipe(Bl$1(kg(this._stopScrolling,this._destroyed))).subscribe(()=>{let{maxScrollDistance:i,distance:a}=this._scrollHeader(e);(a===0||a>=i)&&this._stopInterval();}));}_scrollTo(e){if(this.disablePagination)return {maxScrollDistance:0,distance:0};let t=this._getMaxScrollDistance();return this._scrollDistance=Math.max(0,Math.min(t,e)),this._scrollDistanceChanged=true,this._checkScrollingControls(),{maxScrollDistance:t,distance:this._scrollDistance}}static \u0275fac=function(t){return new(t||n)};static \u0275dir=SE({type:n,inputs:{disablePagination:[2,"disablePagination","disablePagination",j0],selectedIndex:[2,"selectedIndex","selectedIndex",V0]},outputs:{selectFocusedIndex:"selectFocusedIndex",indexFocused:"indexFocused"}})}return n})(),uh=(()=>{class n extends ph{_items;_tabListContainer;_tabList;_tabListInner;_nextPaginator;_previousPaginator;_inkBar;ariaLabel;ariaLabelledby;disableRipple=false;ngAfterContentInit(){this._inkBar=new rr(this._items),super.ngAfterContentInit();}_itemSelected(e){e.preventDefault();}static \u0275fac=(()=>{let e;return function(i){return (e||(e=fy(n)))(i||n)}})();static \u0275cmp=TE({type:n,selectors:[["mat-tab-header"]],contentQueries:function(t,i,a){if(t&1&&ih$1(a,hl,4),t&2){let o;vD(o=ID())&&(i._items=o);}},viewQuery:function(t,i){if(t&1&&sh$1(Um,7)(Wm,7)(qm,7)(Qm,5)(Ym,5),t&2){let a;vD(a=ID())&&(i._tabListContainer=a.first),vD(a=ID())&&(i._tabList=a.first),vD(a=ID())&&(i._tabListInner=a.first),vD(a=ID())&&(i._nextPaginator=a.first),vD(a=ID())&&(i._previousPaginator=a.first);}},hostAttrs:[1,"mat-mdc-tab-header"],hostVars:4,hostBindings:function(t,i){t&2&&fh$1("mat-mdc-tab-header-pagination-controls-enabled",i._showPaginationControls)("mat-mdc-tab-header-rtl",i._getLayoutDirection()=="rtl");},inputs:{ariaLabel:[0,"aria-label","ariaLabel"],ariaLabelledby:[0,"aria-labelledby","ariaLabelledby"],disableRipple:[2,"disableRipple","disableRipple",j0]},features:[Hp$1],ngContentSelectors:lr,decls:13,vars:10,consts:[["previousPaginator",""],["tabListContainer",""],["tabList",""],["tabListInner",""],["nextPaginator",""],["mat-ripple","",1,"mat-mdc-tab-header-pagination","mat-mdc-tab-header-pagination-before",3,"click","mousedown","touchend","matRippleDisabled"],[1,"mat-mdc-tab-header-pagination-chevron"],[1,"mat-mdc-tab-label-container",3,"keydown"],["role","tablist",1,"mat-mdc-tab-list",3,"cdkObserveContent"],[1,"mat-mdc-tab-labels"],["mat-ripple","",1,"mat-mdc-tab-header-pagination","mat-mdc-tab-header-pagination-after",3,"mousedown","click","touchend","matRippleDisabled"]],template:function(t,i){t&1&&(gD(),yi(0,"div",5,0),nh$1("click",function(){return i._handlePaginatorClick("before")})("mousedown",function(o){return i._handlePaginatorPress("before",o)})("touchend",function(){return i._stopInterval()}),Yp(2,"div",6),jc$1(),yi(3,"div",7,1),nh$1("keydown",function(o){return i._handleKeydown(o)}),yi(5,"div",8,2),nh$1("cdkObserveContent",function(){return i._onContentChanges()}),yi(7,"div",9,3),mD(9),jc$1()()(),yi(10,"div",10,4),nh$1("mousedown",function(o){return i._handlePaginatorPress("after",o)})("click",function(){return i._handlePaginatorClick("after")})("touchend",function(){return i._stopInterval()}),Yp(12,"div",6),jc$1()),t&2&&(fh$1("mat-mdc-tab-header-pagination-disabled",i._disableScrollBefore),Zp("matRippleDisabled",i._disableScrollBefore||i.disableRipple),Wv(3),fh$1("_mat-animation-noopable",i._animationsDisabled),Wv(2),Qp("aria-label",i.ariaLabel||null)("aria-labelledby",i.ariaLabelledby||null),Wv(5),fh$1("mat-mdc-tab-header-pagination-disabled",i._disableScrollAfter),Zp("matRippleDisabled",i._disableScrollAfter||i.disableRipple));},dependencies:[rr$1,Qc$1],styles:[`.mat-mdc-tab-header {
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
`],encapsulation:2,changeDetection:1})}return n})(),_h=new x("MAT_TABS_CONFIG"),dl=(()=>{class n extends st{_host=T(or);_ngZone=T(Se);_centeringSub=j.EMPTY;_leavingSub=j.EMPTY;ngOnInit(){super.ngOnInit(),this._centeringSub=this._host._beforeCentering.pipe(Hl$1(this._host._isCenterPosition())).subscribe(e=>{this._host._content&&e&&!this.hasAttached()&&this._ngZone.run(()=>{Promise.resolve().then(),this.attach(this._host._content);});}),this._leavingSub=this._host._afterLeavingCenter.subscribe(()=>{this._host.preserveContent||this._ngZone.run(()=>this.detach());});}ngOnDestroy(){super.ngOnDestroy(),this._centeringSub.unsubscribe(),this._leavingSub.unsubscribe();}static \u0275fac=(()=>{let e;return function(i){return (e||(e=fy(n)))(i||n)}})();static \u0275dir=SE({type:n,selectors:[["","matTabBodyHost",""]],features:[Hp$1]})}return n})(),or=(()=>{class n{_elementRef=T(Dr$1);_dir=T(Er$2,{optional:true});_ngZone=T(Se);_injector=T(ge);_renderer=T(MI);_diAnimationsDisabled=B();_eventCleanups;_initialized=false;_fallbackTimer;_positionIndex;_dirChangeSubscription=j.EMPTY;_position;_previousPosition;_onCentering=new $e;_beforeCentering=new $e;_afterLeavingCenter=new $e;_onCentered=new $e(true);_portalHost;_contentElement;_content;animationDuration="500ms";preserveContent=false;set position(e){this._positionIndex=e,this._computePositionAnimationState();}constructor(){if(this._dir){let e=T(L0);this._dirChangeSubscription=this._dir.change.subscribe(t=>{this._computePositionAnimationState(t),e.markForCheck();});}}ngOnInit(){this._bindTransitionEvents(),this._position==="center"&&(this._setActiveClass(true),Dv(()=>this._onCentering.emit(this._elementRef.nativeElement.clientHeight),{injector:this._injector})),this._initialized=true;}ngOnDestroy(){clearTimeout(this._fallbackTimer),this._eventCleanups?.forEach(e=>e()),this._dirChangeSubscription.unsubscribe();}_bindTransitionEvents(){this._ngZone.runOutsideAngular(()=>{let e=this._elementRef.nativeElement,t=i=>{i.target===this._contentElement?.nativeElement&&(this._elementRef.nativeElement.classList.remove("mat-tab-body-animating"),i.type==="transitionend"&&this._transitionDone());};this._eventCleanups=[this._renderer.listen(e,"transitionstart",i=>{i.target===this._contentElement?.nativeElement&&(this._elementRef.nativeElement.classList.add("mat-tab-body-animating"),this._transitionStarted());}),this._renderer.listen(e,"transitionend",t),this._renderer.listen(e,"transitioncancel",t)];});}_transitionStarted(){clearTimeout(this._fallbackTimer);let e=this._position==="center";this._beforeCentering.emit(e),e&&this._onCentering.emit(this._elementRef.nativeElement.clientHeight);}_transitionDone(){this._position==="center"?this._onCentered.emit():this._previousPosition==="center"&&this._afterLeavingCenter.emit();}_setActiveClass(e){this._elementRef.nativeElement.classList.toggle("mat-mdc-tab-body-active",e);}_getLayoutDirection(){return this._dir&&this._dir.value==="rtl"?"rtl":"ltr"}_isCenterPosition(){return this._positionIndex===0}_computePositionAnimationState(e=this._getLayoutDirection()){this._previousPosition=this._position,this._positionIndex<0?this._position=e=="ltr"?"left":"right":this._positionIndex>0?this._position=e=="ltr"?"right":"left":this._position="center",this._animationsDisabled()?this._simulateTransitionEvents():this._initialized&&(this._position==="center"||this._previousPosition==="center")&&(clearTimeout(this._fallbackTimer),this._fallbackTimer=this._ngZone.runOutsideAngular(()=>setTimeout(()=>this._simulateTransitionEvents(),100)));}_simulateTransitionEvents(){this._transitionStarted(),Dv(()=>this._transitionDone(),{injector:this._injector});}_animationsDisabled(){return this._diAnimationsDisabled||this.animationDuration==="0ms"||this.animationDuration==="0s"}static \u0275fac=function(t){return new(t||n)};static \u0275cmp=TE({type:n,selectors:[["mat-tab-body"]],viewQuery:function(t,i){if(t&1&&sh$1(dl,5)(Km,5),t&2){let a;vD(a=ID())&&(i._portalHost=a.first),vD(a=ID())&&(i._contentElement=a.first);}},hostAttrs:[1,"mat-mdc-tab-body"],hostVars:1,hostBindings:function(t,i){t&2&&Qp("inert",i._position==="center"?null:"");},inputs:{_content:[0,"content","_content"],animationDuration:"animationDuration",preserveContent:"preserveContent",position:"position"},outputs:{_onCentering:"_onCentering",_beforeCentering:"_beforeCentering",_onCentered:"_onCentered"},decls:3,vars:6,consts:[["content",""],["cdkScrollable","",1,"mat-mdc-tab-body-content"],["matTabBodyHost",""]],template:function(t,i){t&1&&(yi(0,"div",1,0),$p(2,$m,0,0,"ng-template",2),jc$1()),t&2&&fh$1("mat-tab-body-content-left",i._position==="left")("mat-tab-body-content-right",i._position==="right")("mat-tab-body-content-can-animate",i._position==="center"||i._previousPosition==="center");},dependencies:[dl,mi$1],styles:[`.mat-mdc-tab-body {
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
`],encapsulation:2,changeDetection:1})}return n})(),y0=(()=>{class n{_elementRef=T(Dr$1);_changeDetectorRef=T(L0);_ngZone=T(Se);_tabsSubscription=j.EMPTY;_tabLabelSubscription=j.EMPTY;_tabBodySubscription=j.EMPTY;_diAnimationsDisabled=B();_bodyAnimationDuration;_headerAnimationDuration;_allTabs;_tabBodies;_tabBodyWrapper;_tabHeader;_tabs=new ri;_indexToSelect=0;_lastFocusedTabIndex=null;_tabBodyWrapperHeight=0;color;get fitInkBarToContent(){return this._fitInkBarToContent}set fitInkBarToContent(e){this._fitInkBarToContent=e,this._changeDetectorRef.markForCheck();}_fitInkBarToContent=false;stretchTabs=true;alignTabs=null;dynamicHeight=false;get selectedIndex(){return this._selectedIndex}set selectedIndex(e){this._indexToSelect=isNaN(e)?null:e;}_selectedIndex=null;headerPosition="above";get animationDuration(){return this._animationDuration}set animationDuration(e){this._animationDuration=e,e&&typeof e=="object"?(this._bodyAnimationDuration=ar(e.body),this._headerAnimationDuration=ar(e.header)):this._headerAnimationDuration=this._bodyAnimationDuration=ar(e);}_animationDuration;get contentTabIndex(){return this._contentTabIndex}set contentTabIndex(e){this._contentTabIndex=isNaN(e)?null:e;}_contentTabIndex=null;disablePagination=false;disableRipple=false;preserveContent=false;get backgroundColor(){return this._backgroundColor}set backgroundColor(e){let t=this._elementRef.nativeElement.classList;t.remove("mat-tabs-with-background",`mat-background-${this.backgroundColor}`),e&&t.add("mat-tabs-with-background",`mat-background-${e}`),this._backgroundColor=e;}_backgroundColor;ariaLabel;ariaLabelledby;selectedIndexChange=new $e;focusChange=new $e;animationDone=new $e;selectedTabChange=new $e(true);_groupId;_isServer=!T(D).isBrowser;constructor(){let e=T(_h,{optional:true});this._groupId=T(Y).getId("mat-tab-group-"),this.animationDuration=e&&e.animationDuration?e.animationDuration:"500ms",this.disablePagination=e&&e.disablePagination!=null?e.disablePagination:false,this.dynamicHeight=e&&e.dynamicHeight!=null?e.dynamicHeight:false,e?.contentTabIndex!=null&&(this.contentTabIndex=e.contentTabIndex),this.preserveContent=!!e?.preserveContent,this.fitInkBarToContent=e&&e.fitInkBarToContent!=null?e.fitInkBarToContent:false,this.stretchTabs=e&&e.stretchTabs!=null?e.stretchTabs:true,this.alignTabs=e&&e.alignTabs!=null?e.alignTabs:null;}ngAfterContentChecked(){let e=this._indexToSelect=this._clampTabIndex(this._indexToSelect);if(this._selectedIndex!=e){let t=this._selectedIndex==null;if(!t){this.selectedTabChange.emit(this._createChangeEvent(e));let i=this._tabBodyWrapper.nativeElement;i.style.minHeight=i.clientHeight+"px";}Promise.resolve().then(()=>{this._tabs.forEach((i,a)=>i.isActive=a===e),t||(this.selectedIndexChange.emit(e),this._tabBodyWrapper.nativeElement.style.minHeight="");});}this._tabs.forEach((t,i)=>{t.position=i-e,this._selectedIndex!=null&&t.position==0&&!t.origin&&(t.origin=e-this._selectedIndex);}),this._selectedIndex!==e&&(this._selectedIndex=e,this._lastFocusedTabIndex=null,this._changeDetectorRef.markForCheck());}ngAfterContentInit(){this._subscribeToAllTabChanges(),this._subscribeToTabLabels(),this._tabsSubscription=this._tabs.changes.subscribe(()=>{let e=this._clampTabIndex(this._indexToSelect);if(e===this._selectedIndex){let t=this._tabs.toArray(),i;for(let a=0;a<t.length;a++)if(t[a].isActive){this._indexToSelect=this._selectedIndex=a,this._lastFocusedTabIndex=null,i=t[a];break}!i&&t[e]&&Promise.resolve().then(()=>{t[e].isActive=true,this.selectedTabChange.emit(this._createChangeEvent(e));});}this._changeDetectorRef.markForCheck();});}ngAfterViewInit(){this._tabBodySubscription=this._tabBodies.changes.subscribe(()=>this._bodyCentered(true));}_subscribeToAllTabChanges(){this._allTabs.changes.pipe(Hl$1(this._allTabs)).subscribe(e=>{this._tabs.reset(e.filter(t=>t._closestTabGroup===this||!t._closestTabGroup)),this._tabs.notifyOnChanges();});}ngOnDestroy(){this._tabs.destroy(),this._tabsSubscription.unsubscribe(),this._tabLabelSubscription.unsubscribe(),this._tabBodySubscription.unsubscribe();}realignInkBar(){this._tabHeader&&this._tabHeader._alignInkBarToSelectedTab();}updatePagination(){this._tabHeader&&this._tabHeader.updatePagination();}focusTab(e){let t=this._tabHeader;t&&(t.focusIndex=e);}_focusChanged(e){this._lastFocusedTabIndex=e,this.focusChange.emit(this._createChangeEvent(e));}_createChangeEvent(e){let t=new sr;return t.index=e,this._tabs&&this._tabs.length&&(t.tab=this._tabs.toArray()[e]),t}_subscribeToTabLabels(){this._tabLabelSubscription&&this._tabLabelSubscription.unsubscribe(),this._tabLabelSubscription=kg(...this._tabs.map(e=>e._stateChanges)).subscribe(()=>this._changeDetectorRef.markForCheck());}_clampTabIndex(e){return Math.min(this._tabs.length-1,Math.max(e||0,0))}_getTabLabelId(e,t){return e.id||`${this._groupId}-label-${t}`}_getTabContentId(e){return `${this._groupId}-content-${e}`}_setTabBodyWrapperHeight(e){if(!this.dynamicHeight||!this._tabBodyWrapperHeight){this._tabBodyWrapperHeight=e;return}let t=this._tabBodyWrapper.nativeElement;t.style.height=this._tabBodyWrapperHeight+"px",this._tabBodyWrapper.nativeElement.offsetHeight&&(t.style.height=e+"px");}_removeTabBodyWrapperHeight(){let e=this._tabBodyWrapper.nativeElement;this._tabBodyWrapperHeight=e.clientHeight,e.style.height="",this._ngZone.run(()=>this.animationDone.emit());}_handleClick(e,t,i){t.focusIndex=i,e.disabled||(this.selectedIndex=i);}_getTabIndex(e){let t=this._lastFocusedTabIndex??this.selectedIndex;return e===t?0:-1}_tabFocusChanged(e,t){e&&e!=="mouse"&&e!=="touch"&&(this._tabHeader.focusIndex=t);}_bodyCentered(e){e&&this._tabBodies?.forEach((t,i)=>t._setActiveClass(i===this._selectedIndex));}_bodyAnimationsDisabled(){return this._diAnimationsDisabled||this._bodyAnimationDuration==="0"||this._bodyAnimationDuration==="0ms"}static \u0275fac=function(t){return new(t||n)};static \u0275cmp=TE({type:n,selectors:[["mat-tab-group"]],contentQueries:function(t,i,a){if(t&1&&ih$1(a,dh,5),t&2){let o;vD(o=ID())&&(i._allTabs=o);}},viewQuery:function(t,i){if(t&1&&sh$1(Xm,5)(Zm,5)(or,5),t&2){let a;vD(a=ID())&&(i._tabBodyWrapper=a.first),vD(a=ID())&&(i._tabHeader=a.first),vD(a=ID())&&(i._tabBodies=a);}},hostAttrs:[1,"mat-mdc-tab-group"],hostVars:13,hostBindings:function(t,i){t&2&&(Qp("mat-align-tabs",i.alignTabs),RD("mat-"+(i.color||"primary")),dh$1("--mat-tab-body-animation-duration",i._bodyAnimationDuration)("--mat-tab-header-animation-duration",i._headerAnimationDuration),fh$1("mat-mdc-tab-group-dynamic-height",i.dynamicHeight)("mat-mdc-tab-group-inverted-header",i.headerPosition==="below")("mat-mdc-tab-group-stretch-tabs",i.stretchTabs));},inputs:{color:"color",fitInkBarToContent:[2,"fitInkBarToContent","fitInkBarToContent",j0],stretchTabs:[2,"mat-stretch-tabs","stretchTabs",j0],alignTabs:[0,"mat-align-tabs","alignTabs"],dynamicHeight:[2,"dynamicHeight","dynamicHeight",j0],selectedIndex:[2,"selectedIndex","selectedIndex",V0],headerPosition:"headerPosition",animationDuration:"animationDuration",contentTabIndex:[2,"contentTabIndex","contentTabIndex",V0],disablePagination:[2,"disablePagination","disablePagination",j0],disableRipple:[2,"disableRipple","disableRipple",j0],preserveContent:[2,"preserveContent","preserveContent",j0],backgroundColor:"backgroundColor",ariaLabel:[0,"aria-label","ariaLabel"],ariaLabelledby:[0,"aria-labelledby","ariaLabelledby"]},outputs:{selectedIndexChange:"selectedIndexChange",focusChange:"focusChange",animationDone:"animationDone",selectedTabChange:"selectedTabChange"},exportAs:["matTabGroup"],features:[JD([{provide:ml,useExisting:n}])],ngContentSelectors:lr,decls:9,vars:8,consts:[["tabHeader",""],["tabBodyWrapper",""],["tabNode",""],[3,"indexFocused","selectFocusedIndex","selectedIndex","disableRipple","disablePagination","aria-label","aria-labelledby"],["role","tab","matTabLabelWrapper","","cdkMonitorElementFocus","",1,"mdc-tab","mat-mdc-tab","mat-focus-indicator",3,"id","mdc-tab--active","class","disabled","fitInkBarToContent"],[1,"mat-mdc-tab-body-wrapper"],["role","tabpanel",3,"id","class","content","position","animationDuration","preserveContent"],["role","tab","matTabLabelWrapper","","cdkMonitorElementFocus","",1,"mdc-tab","mat-mdc-tab","mat-focus-indicator",3,"click","cdkFocusChange","id","disabled","fitInkBarToContent"],[1,"mdc-tab__ripple"],["mat-ripple","",1,"mat-mdc-tab-ripple",3,"matRippleTrigger","matRippleDisabled"],[1,"mdc-tab__content"],[1,"mdc-tab__text-label"],[3,"cdkPortalOutlet"],["role","tabpanel",3,"_onCentered","_onCentering","_beforeCentering","id","content","position","animationDuration","preserveContent"]],template:function(t,i){t&1&&(gD(),yi(0,"mat-tab-header",3,0),nh$1("indexFocused",function(o){return i._focusChanged(o)})("selectFocusedIndex",function(o){return i.selectedIndex=o}),JE(2,ih,8,17,"div",4,KE),jc$1(),QE(4,nh,1,0),yi(5,"div",5,1),JE(7,ah,1,10,"mat-tab-body",6,KE),jc$1()),t&2&&(Zp("selectedIndex",i.selectedIndex||0)("disableRipple",i.disableRipple)("disablePagination",i.disablePagination),zp$1("aria-label",i.ariaLabel)("aria-labelledby",i.ariaLabelledby),Wv(2),XE(i._tabs),Wv(2),ZE(i._isServer?4:-1),Wv(),fh$1("_mat-animation-noopable",i._bodyAnimationsDisabled()),Wv(2),XE(i._tabs));},dependencies:[uh,hl,ba$1,rr$1,st,or],styles:[`.mdc-tab {
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
`],encapsulation:2,changeDetection:1})}return n})(),sr=class{index;tab};var dr=(()=>{class n{static \u0275fac=function(t){return new(t||n)};static \u0275mod=bE({type:n});static \u0275inj=cu$1({imports:[jo$1]})}return n})();var cr=(()=>{class n{static \u0275fac=function(t){return new(t||n)};static \u0275mod=bE({type:n});static \u0275inj=cu$1({imports:[ti,jo$1]})}return n})();var fh=["button"],gh=["*"];function bh(n,r){if(n&1&&(yi(0,"div",2),Yp(1,"mat-pseudo-checkbox",6),jc$1()),n&2){let e=pD();Wv(),Zp("disabled",e.disabled);}}var pl=new x("MAT_BUTTON_TOGGLE_DEFAULT_OPTIONS",{providedIn:"root",factory:()=>({hideSingleSelectionIndicator:false,hideMultipleSelectionIndicator:false,disabledInteractive:false})}),ul=new x("MatButtonToggleGroup"),vh={provide:Xe,useExisting:vo(()=>yh),multi:true},zn=class{source;value;constructor(r,e){this.source=r,this.value=e;}},yh=(()=>{class n{_changeDetector=T(L0);_dir=T(Er$2,{optional:true});_multiple=false;_disabled=false;_disabledInteractive=false;_selectionModel;_rawValue;_controlValueAccessorChangeFn=()=>{};_onTouched=()=>{};_buttonToggles;appearance;get name(){return this._name}set name(e){this._name=e,this._markButtonsForCheck();}_name=T(Y).getId("mat-button-toggle-group-");vertical=false;get value(){let e=this._selectionModel?this._selectionModel.selected:[];return this.multiple?e.map(t=>t.value):e[0]?e[0].value:void 0}set value(e){this._setSelectionByValue(e),this.valueChange.emit(this.value);}valueChange=new $e;get selected(){let e=this._selectionModel?this._selectionModel.selected:[];return this.multiple?e:e[0]||null}get multiple(){return this._multiple}set multiple(e){this._multiple=e,this._markButtonsForCheck();}get disabled(){return this._disabled}set disabled(e){this._disabled=e,this._markButtonsForCheck();}get disabledInteractive(){return this._disabledInteractive}set disabledInteractive(e){this._disabledInteractive=e,this._markButtonsForCheck();}get dir(){return this._dir&&this._dir.value==="rtl"?"rtl":"ltr"}change=new $e;get hideSingleSelectionIndicator(){return this._hideSingleSelectionIndicator}set hideSingleSelectionIndicator(e){this._hideSingleSelectionIndicator=e,this._markButtonsForCheck();}_hideSingleSelectionIndicator;get hideMultipleSelectionIndicator(){return this._hideMultipleSelectionIndicator}set hideMultipleSelectionIndicator(e){this._hideMultipleSelectionIndicator=e,this._markButtonsForCheck();}_hideMultipleSelectionIndicator;constructor(){let e=T(pl,{optional:true});this.appearance=e&&e.appearance?e.appearance:"standard",this._hideSingleSelectionIndicator=e?.hideSingleSelectionIndicator??false,this._hideMultipleSelectionIndicator=e?.hideMultipleSelectionIndicator??false;}ngOnInit(){this._selectionModel=new Qt(this.multiple,void 0,false);}ngAfterContentInit(){this._selectionModel.select(...this._buttonToggles.filter(e=>e.checked)),this.multiple||this._initializeTabIndex();}writeValue(e){this.value=e,this._changeDetector.markForCheck();}registerOnChange(e){this._controlValueAccessorChangeFn=e;}registerOnTouched(e){this._onTouched=e;}setDisabledState(e){this.disabled=e;}_keydown(e){if(this.multiple||this.disabled||$(e))return;let i=e.target.id,a=this._buttonToggles.toArray().findIndex(c=>c.buttonId===i),o=null;switch(e.keyCode){case 32:case 13:o=this._buttonToggles.get(a)||null;break;case 38:o=this._getNextButton(a,-1);break;case 37:o=this._getNextButton(a,this.dir==="ltr"?-1:1);break;case 40:o=this._getNextButton(a,1);break;case 39:o=this._getNextButton(a,this.dir==="ltr"?1:-1);break;default:return}o&&(e.preventDefault(),o._onButtonClick(),o.focus());}_emitChangeEvent(e){let t=new zn(e,this.value);this._rawValue=t.value,this._controlValueAccessorChangeFn(t.value),this.change.emit(t);}_syncButtonToggle(e,t,i=false,a=false){!this.multiple&&this.selected&&!e.checked&&(this.selected.checked=false),this._selectionModel?t?this._selectionModel.select(e):this._selectionModel.deselect(e):a=true,a?Promise.resolve().then(()=>this._updateModelValue(e,i)):this._updateModelValue(e,i);}_isSelected(e){return this._selectionModel&&this._selectionModel.isSelected(e)}_isPrechecked(e){return typeof this._rawValue>"u"?false:this.multiple&&Array.isArray(this._rawValue)?this._rawValue.some(t=>e.value!=null&&t===e.value):e.value===this._rawValue}_initializeTabIndex(){if(this._buttonToggles.forEach(e=>{e.tabIndex=-1;}),this.selected)this.selected.tabIndex=0;else for(let e=0;e<this._buttonToggles.length;e++){let t=this._buttonToggles.get(e);if(!t.disabled){t.tabIndex=0;break}}}_getNextButton(e,t){let i=this._buttonToggles;for(let a=1;a<=i.length;a++){let o=(e+t*a+i.length)%i.length,c=i.get(o);if(c&&!c.disabled)return c}return null}_setSelectionByValue(e){if(this._rawValue=e,!this._buttonToggles)return;let t=this._buttonToggles.toArray();if(this.multiple&&e?(this._clearSelection(),e.forEach(i=>this._selectValue(i,t))):(this._clearSelection(),this._selectValue(e,t)),!this.multiple&&t.every(i=>i.tabIndex===-1)){for(let i of t)if(!i.disabled){i.tabIndex=0;break}}}_clearSelection(){this._selectionModel.clear(),this._buttonToggles.forEach(e=>{e.checked=false,this.multiple||(e.tabIndex=-1);});}_selectValue(e,t){for(let i of t)if(i.value===e){i.checked=true,this._selectionModel.select(i),this.multiple||(i.tabIndex=0);break}}_updateModelValue(e,t){t&&this._emitChangeEvent(e),this.valueChange.emit(this.value);}_markButtonsForCheck(){this._buttonToggles?.forEach(e=>e._markForCheck());}static \u0275fac=function(t){return new(t||n)};static \u0275dir=SE({type:n,selectors:[["mat-button-toggle-group"]],contentQueries:function(t,i,a){if(t&1&&ih$1(a,_l,5),t&2){let o;vD(o=ID())&&(i._buttonToggles=o);}},hostAttrs:[1,"mat-button-toggle-group"],hostVars:6,hostBindings:function(t,i){t&1&&nh$1("keydown",function(o){return i._keydown(o)}),t&2&&(Qp("role",i.multiple?"group":"radiogroup")("aria-disabled",i.disabled),fh$1("mat-button-toggle-vertical",i.vertical)("mat-button-toggle-group-appearance-standard",i.appearance==="standard"));},inputs:{appearance:"appearance",name:"name",vertical:[2,"vertical","vertical",j0],value:"value",multiple:[2,"multiple","multiple",j0],disabled:[2,"disabled","disabled",j0],disabledInteractive:[2,"disabledInteractive","disabledInteractive",j0],hideSingleSelectionIndicator:[2,"hideSingleSelectionIndicator","hideSingleSelectionIndicator",j0],hideMultipleSelectionIndicator:[2,"hideMultipleSelectionIndicator","hideMultipleSelectionIndicator",j0]},outputs:{valueChange:"valueChange",change:"change"},exportAs:["matButtonToggleGroup"],features:[JD([vh,{provide:ul,useExisting:n}])]})}return n})(),_l=(()=>{class n{_changeDetectorRef=T(L0);_elementRef=T(Dr$1);_focusMonitor=T(le);_idGenerator=T(Y);_animationDisabled=B();_checked=false;ariaLabel;ariaLabelledby=null;_buttonElement;buttonToggleGroup;get buttonId(){return `${this.id}-button`}id;name;value;get tabIndex(){return this._tabIndex()}set tabIndex(e){this._tabIndex.set(e);}_tabIndex;disableRipple=false;get appearance(){return this.buttonToggleGroup?this.buttonToggleGroup.appearance:this._appearance}set appearance(e){this._appearance=e;}_appearance;get checked(){return this.buttonToggleGroup?this.buttonToggleGroup._isSelected(this):this._checked}set checked(e){e!==this._checked&&(this._checked=e,this.buttonToggleGroup&&this.buttonToggleGroup._syncButtonToggle(this,this._checked),this._changeDetectorRef.markForCheck());}get disabled(){return this._disabled||this.buttonToggleGroup&&this.buttonToggleGroup.disabled}set disabled(e){this._disabled=e;}_disabled=false;get disabledInteractive(){return this._disabledInteractive||this.buttonToggleGroup!==null&&this.buttonToggleGroup.disabledInteractive}set disabledInteractive(e){this._disabledInteractive=e;}_disabledInteractive;change=new $e;constructor(){T(W$1).load(bn$1);let e=T(ul,{optional:true}),t=T(new Ah$1("tabindex"),{optional:true})||"",i=T(pl,{optional:true});this._tabIndex=Vo$1(parseInt(t)||0),this.buttonToggleGroup=e,this._appearance=i&&i.appearance?i.appearance:"standard",this._disabledInteractive=i?.disabledInteractive??false;}ngOnInit(){let e=this.buttonToggleGroup;this.id=this.id||this._idGenerator.getId("mat-button-toggle-"),e&&(e._isPrechecked(this)?this.checked=true:e._isSelected(this)!==this._checked&&e._syncButtonToggle(this,this._checked));}ngAfterViewInit(){this._animationDisabled||this._elementRef.nativeElement.classList.add("mat-button-toggle-animations-enabled"),this._focusMonitor.monitor(this._elementRef,true);}ngOnDestroy(){let e=this.buttonToggleGroup;this._focusMonitor.stopMonitoring(this._elementRef),e&&e._isSelected(this)&&e._syncButtonToggle(this,false,false,true);}focus(e){this._buttonElement.nativeElement.focus(e);}_onButtonClick(){if(this.disabled)return;let e=this.isSingleSelector()?true:!this._checked;if(e!==this._checked&&(this._checked=e,this.buttonToggleGroup&&(this.buttonToggleGroup._syncButtonToggle(this,this._checked,true),this.buttonToggleGroup._onTouched())),this.isSingleSelector()){let t=this.buttonToggleGroup._buttonToggles.find(i=>i.tabIndex===0);t&&(t.tabIndex=-1),this.tabIndex=0;}this.change.emit(new zn(this,this.value));}_markForCheck(){this._changeDetectorRef.markForCheck();}_getButtonName(){return this.isSingleSelector()?this.buttonToggleGroup.name:this.name||null}isSingleSelector(){return this.buttonToggleGroup&&!this.buttonToggleGroup.multiple}static \u0275fac=function(t){return new(t||n)};static \u0275cmp=TE({type:n,selectors:[["mat-button-toggle"]],viewQuery:function(t,i){if(t&1&&sh$1(fh,5),t&2){let a;vD(a=ID())&&(i._buttonElement=a.first);}},hostAttrs:["role","presentation",1,"mat-button-toggle"],hostVars:14,hostBindings:function(t,i){t&1&&nh$1("focus",function(){return i.focus()}),t&2&&(Qp("aria-label",null)("aria-labelledby",null)("id",i.id)("name",null),fh$1("mat-button-toggle-standalone",!i.buttonToggleGroup)("mat-button-toggle-checked",i.checked)("mat-button-toggle-disabled",i.disabled)("mat-button-toggle-disabled-interactive",i.disabledInteractive)("mat-button-toggle-appearance-standard",i.appearance==="standard"));},inputs:{ariaLabel:[0,"aria-label","ariaLabel"],ariaLabelledby:[0,"aria-labelledby","ariaLabelledby"],id:"id",name:"name",value:"value",tabIndex:"tabIndex",disableRipple:[2,"disableRipple","disableRipple",j0],appearance:"appearance",checked:[2,"checked","checked",j0],disabled:[2,"disabled","disabled",j0],disabledInteractive:[2,"disabledInteractive","disabledInteractive",j0]},outputs:{change:"change"},exportAs:["matButtonToggle"],ngContentSelectors:gh,decls:7,vars:13,consts:[["button",""],["type","button",1,"mat-button-toggle-button","mat-focus-indicator",3,"click","id","disabled"],[1,"mat-button-toggle-checkbox-wrapper"],[1,"mat-button-toggle-label-content"],[1,"mat-button-toggle-focus-overlay"],["matRipple","",1,"mat-button-toggle-ripple",3,"matRippleTrigger","matRippleDisabled"],["state","checked","aria-hidden","true","appearance","minimal",3,"disabled"]],template:function(t,i){if(t&1&&(gD(),yi(0,"button",1,0),nh$1("click",function(){return i._onButtonClick()}),QE(2,bh,2,1,"div",2),yi(3,"span",3),mD(4),jc$1()(),Yp(5,"span",4)(6,"span",5)),t&2){let a=DD(1);Zp("id",i.buttonId)("disabled",i.disabled&&!i.disabledInteractive||null),Qp("role",i.isSingleSelector()?"radio":"button")("tabindex",i.disabled&&!i.disabledInteractive?-1:i.tabIndex)("aria-pressed",i.isSingleSelector()?null:i.checked)("aria-checked",i.isSingleSelector()?i.checked:null)("name",i._getButtonName())("aria-label",i.ariaLabel)("aria-labelledby",i.ariaLabelledby)("aria-disabled",i.disabled&&i.disabledInteractive?"true":null),Wv(2),ZE(i.buttonToggleGroup&&(!i.buttonToggleGroup.multiple&&!i.buttonToggleGroup.hideSingleSelectionIndicator||i.buttonToggleGroup.multiple&&!i.buttonToggleGroup.hideMultipleSelectionIndicator)?2:-1),Wv(4),Zp("matRippleTrigger",a)("matRippleDisabled",i.disableRipple||i.disabled);}},dependencies:[rr$1,ar$1],styles:[`.mat-button-toggle-standalone,
.mat-button-toggle-group {
  position: relative;
  display: inline-flex;
  flex-direction: row;
  white-space: nowrap;
  overflow: hidden;
  -webkit-tap-highlight-color: transparent;
  border-radius: var(--mat-button-toggle-legacy-shape);
  transform: translateZ(0);
}
.mat-button-toggle-standalone:not([class*=mat-elevation-z]),
.mat-button-toggle-group:not([class*=mat-elevation-z]) {
  box-shadow: 0px 3px 1px -2px rgba(0, 0, 0, 0.2), 0px 2px 2px 0px rgba(0, 0, 0, 0.14), 0px 1px 5px 0px rgba(0, 0, 0, 0.12);
}
@media (forced-colors: active) {
  .mat-button-toggle-standalone,
  .mat-button-toggle-group {
    outline: solid 1px;
  }
}

.mat-button-toggle-standalone.mat-button-toggle-appearance-standard,
.mat-button-toggle-group-appearance-standard {
  border-radius: var(--mat-button-toggle-shape, var(--mat-sys-corner-extra-large));
  border: solid 1px var(--mat-button-toggle-divider-color, var(--mat-sys-outline));
}
.mat-button-toggle-standalone.mat-button-toggle-appearance-standard .mat-pseudo-checkbox,
.mat-button-toggle-group-appearance-standard .mat-pseudo-checkbox {
  --mat-pseudo-checkbox-minimal-selected-checkmark-color: var(--mat-button-toggle-selected-state-text-color, var(--mat-sys-on-secondary-container));
}
.mat-button-toggle-standalone.mat-button-toggle-appearance-standard:not([class*=mat-elevation-z]),
.mat-button-toggle-group-appearance-standard:not([class*=mat-elevation-z]) {
  box-shadow: none;
}
@media (forced-colors: active) {
  .mat-button-toggle-standalone.mat-button-toggle-appearance-standard,
  .mat-button-toggle-group-appearance-standard {
    outline: 0;
  }
}

.mat-button-toggle-vertical {
  flex-direction: column;
}
.mat-button-toggle-vertical .mat-button-toggle-label-content {
  display: block;
}

.mat-button-toggle {
  white-space: nowrap;
  position: relative;
  color: var(--mat-button-toggle-legacy-text-color);
  font-family: var(--mat-button-toggle-legacy-label-text-font);
  font-size: var(--mat-button-toggle-legacy-label-text-size);
  line-height: var(--mat-button-toggle-legacy-label-text-line-height);
  font-weight: var(--mat-button-toggle-legacy-label-text-weight);
  letter-spacing: var(--mat-button-toggle-legacy-label-text-tracking);
  --mat-pseudo-checkbox-minimal-selected-checkmark-color: var(--mat-button-toggle-legacy-selected-state-text-color);
}
.mat-button-toggle.cdk-keyboard-focused .mat-button-toggle-focus-overlay {
  opacity: var(--mat-button-toggle-legacy-focus-state-layer-opacity);
}
.mat-button-toggle .mat-icon svg {
  vertical-align: top;
}

.mat-button-toggle-checkbox-wrapper {
  display: inline-block;
  justify-content: flex-start;
  align-items: center;
  width: 0;
  height: 18px;
  line-height: 18px;
  overflow: hidden;
  box-sizing: border-box;
  position: absolute;
  top: 50%;
  left: 16px;
  transform: translate3d(0, -50%, 0);
}
[dir=rtl] .mat-button-toggle-checkbox-wrapper {
  left: auto;
  right: 16px;
}
.mat-button-toggle-appearance-standard .mat-button-toggle-checkbox-wrapper {
  left: 12px;
}
[dir=rtl] .mat-button-toggle-appearance-standard .mat-button-toggle-checkbox-wrapper {
  left: auto;
  right: 12px;
}
.mat-button-toggle-checked .mat-button-toggle-checkbox-wrapper {
  width: 18px;
}
.mat-button-toggle-animations-enabled .mat-button-toggle-checkbox-wrapper {
  transition: width 150ms 45ms cubic-bezier(0.4, 0, 0.2, 1);
}
.mat-button-toggle-vertical .mat-button-toggle-checkbox-wrapper {
  transition: none;
}

.mat-button-toggle-checked {
  color: var(--mat-button-toggle-legacy-selected-state-text-color);
  background-color: var(--mat-button-toggle-legacy-selected-state-background-color);
}

.mat-button-toggle-disabled {
  pointer-events: none;
  color: var(--mat-button-toggle-legacy-disabled-state-text-color);
  background-color: var(--mat-button-toggle-legacy-disabled-state-background-color);
  --mat-pseudo-checkbox-minimal-disabled-selected-checkmark-color: var(--mat-button-toggle-legacy-disabled-state-text-color);
}
.mat-button-toggle-disabled.mat-button-toggle-checked {
  background-color: var(--mat-button-toggle-legacy-disabled-selected-state-background-color);
}

.mat-button-toggle-disabled-interactive {
  pointer-events: auto;
}

.mat-button-toggle-appearance-standard {
  color: var(--mat-button-toggle-text-color, var(--mat-sys-on-surface));
  background-color: var(--mat-button-toggle-background-color, transparent);
  font-family: var(--mat-button-toggle-label-text-font, var(--mat-sys-label-large-font));
  font-size: var(--mat-button-toggle-label-text-size, var(--mat-sys-label-large-size));
  line-height: var(--mat-button-toggle-label-text-line-height, var(--mat-sys-label-large-line-height));
  font-weight: var(--mat-button-toggle-label-text-weight, var(--mat-sys-label-large-weight));
  letter-spacing: var(--mat-button-toggle-label-text-tracking, var(--mat-sys-label-large-tracking));
}
.mat-button-toggle-group-appearance-standard .mat-button-toggle-appearance-standard + .mat-button-toggle-appearance-standard {
  border-left: solid 1px var(--mat-button-toggle-divider-color, var(--mat-sys-outline));
}
[dir=rtl] .mat-button-toggle-group-appearance-standard .mat-button-toggle-appearance-standard + .mat-button-toggle-appearance-standard {
  border-left: none;
  border-right: solid 1px var(--mat-button-toggle-divider-color, var(--mat-sys-outline));
}
.mat-button-toggle-group-appearance-standard.mat-button-toggle-vertical .mat-button-toggle-appearance-standard + .mat-button-toggle-appearance-standard {
  border-left: none;
  border-right: none;
  border-top: solid 1px var(--mat-button-toggle-divider-color, var(--mat-sys-outline));
}
.mat-button-toggle-appearance-standard.mat-button-toggle-checked {
  color: var(--mat-button-toggle-selected-state-text-color, var(--mat-sys-on-secondary-container));
  background-color: var(--mat-button-toggle-selected-state-background-color, var(--mat-sys-secondary-container));
}
.mat-button-toggle-appearance-standard.mat-button-toggle-disabled {
  color: var(--mat-button-toggle-disabled-state-text-color, color-mix(in srgb, var(--mat-sys-on-surface) 38%, transparent));
  background-color: var(--mat-button-toggle-disabled-state-background-color, transparent);
}
.mat-button-toggle-appearance-standard.mat-button-toggle-disabled .mat-pseudo-checkbox {
  --mat-pseudo-checkbox-minimal-disabled-selected-checkmark-color: var(--mat-button-toggle-disabled-selected-state-text-color, color-mix(in srgb, var(--mat-sys-on-surface) 38%, transparent));
}
.mat-button-toggle-appearance-standard.mat-button-toggle-disabled.mat-button-toggle-checked {
  color: var(--mat-button-toggle-disabled-selected-state-text-color, color-mix(in srgb, var(--mat-sys-on-surface) 38%, transparent));
  background-color: var(--mat-button-toggle-disabled-selected-state-background-color, color-mix(in srgb, var(--mat-sys-on-surface) 12%, transparent));
}
.mat-button-toggle-appearance-standard .mat-button-toggle-focus-overlay {
  background-color: var(--mat-button-toggle-state-layer-color, var(--mat-sys-on-surface));
}
.mat-button-toggle-appearance-standard:hover .mat-button-toggle-focus-overlay {
  opacity: var(--mat-button-toggle-hover-state-layer-opacity, var(--mat-sys-hover-state-layer-opacity));
}
.mat-button-toggle-appearance-standard.cdk-keyboard-focused .mat-button-toggle-focus-overlay {
  opacity: var(--mat-button-toggle-focus-state-layer-opacity, var(--mat-sys-focus-state-layer-opacity));
}
@media (hover: none) {
  .mat-button-toggle-appearance-standard:hover .mat-button-toggle-focus-overlay {
    display: none;
  }
}

.mat-button-toggle-label-content {
  -webkit-user-select: none;
  user-select: none;
  display: inline-block;
  padding: 0 16px;
  line-height: var(--mat-button-toggle-legacy-height);
  position: relative;
}
.mat-button-toggle-appearance-standard .mat-button-toggle-label-content {
  padding: 0 12px;
  line-height: var(--mat-button-toggle-height, 40px);
}

.mat-button-toggle-label-content > * {
  vertical-align: middle;
}

.mat-button-toggle-focus-overlay {
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  position: absolute;
  border-radius: inherit;
  pointer-events: none;
  opacity: 0;
  background-color: var(--mat-button-toggle-legacy-state-layer-color);
}

@media (forced-colors: active) {
  .mat-button-toggle-checked .mat-button-toggle-focus-overlay {
    border-bottom: solid 500px;
    opacity: 0.5;
    height: 0;
  }
  .mat-button-toggle-checked:hover .mat-button-toggle-focus-overlay {
    opacity: 0.6;
  }
  .mat-button-toggle-checked.mat-button-toggle-appearance-standard .mat-button-toggle-focus-overlay {
    border-bottom: solid 500px;
  }
}
.mat-button-toggle .mat-button-toggle-ripple {
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  position: absolute;
  pointer-events: none;
}

.mat-button-toggle-button {
  border: 0;
  background: none;
  color: inherit;
  padding: 0;
  margin: 0;
  font: inherit;
  outline: none;
  width: 100%;
  cursor: pointer;
}
.mat-button-toggle-animations-enabled .mat-button-toggle-button {
  transition: padding 150ms 45ms cubic-bezier(0.4, 0, 0.2, 1);
}
.mat-button-toggle-vertical .mat-button-toggle-button {
  transition: none;
}
.mat-button-toggle-disabled .mat-button-toggle-button {
  cursor: default;
}
.mat-button-toggle-button::-moz-focus-inner {
  border: 0;
}
.mat-button-toggle-checked .mat-button-toggle-button:has(.mat-button-toggle-checkbox-wrapper) {
  padding-left: 30px;
}
[dir=rtl] .mat-button-toggle-checked .mat-button-toggle-button:has(.mat-button-toggle-checkbox-wrapper) {
  padding-left: 0;
  padding-right: 30px;
}

.mat-button-toggle-standalone.mat-button-toggle-appearance-standard {
  --mat-focus-indicator-border-radius: var(--mat-button-toggle-shape, var(--mat-sys-corner-extra-large));
}

.mat-button-toggle-group-appearance-standard:not(.mat-button-toggle-vertical) .mat-button-toggle:last-of-type .mat-button-toggle-button::before {
  border-top-right-radius: var(--mat-button-toggle-shape, var(--mat-sys-corner-extra-large));
  border-bottom-right-radius: var(--mat-button-toggle-shape, var(--mat-sys-corner-extra-large));
}
.mat-button-toggle-group-appearance-standard:not(.mat-button-toggle-vertical) .mat-button-toggle:first-of-type .mat-button-toggle-button::before {
  border-top-left-radius: var(--mat-button-toggle-shape, var(--mat-sys-corner-extra-large));
  border-bottom-left-radius: var(--mat-button-toggle-shape, var(--mat-sys-corner-extra-large));
}

.mat-button-toggle-group-appearance-standard.mat-button-toggle-vertical .mat-button-toggle:last-of-type .mat-button-toggle-button::before {
  border-bottom-right-radius: var(--mat-button-toggle-shape, var(--mat-sys-corner-extra-large));
  border-bottom-left-radius: var(--mat-button-toggle-shape, var(--mat-sys-corner-extra-large));
}
.mat-button-toggle-group-appearance-standard.mat-button-toggle-vertical .mat-button-toggle:first-of-type .mat-button-toggle-button::before {
  border-top-right-radius: var(--mat-button-toggle-shape, var(--mat-sys-corner-extra-large));
  border-top-left-radius: var(--mat-button-toggle-shape, var(--mat-sys-corner-extra-large));
}
`],encapsulation:2})}return n})(),mr=(()=>{class n{static \u0275fac=function(t){return new(t||n)};static \u0275mod=bE({type:n});static \u0275inj=cu$1({imports:[Ye,_l,jo$1]})}return n})();var xh=new x("mat-chips-default-options",{providedIn:"root",factory:()=>({separatorKeyCodes:[13]})});var hr=(()=>{class n{static \u0275fac=function(t){return new(t||n)};static \u0275mod=bE({type:n});static \u0275inj=cu$1({providers:[nf,{provide:xh,useValue:{separatorKeyCodes:[13]}}],imports:[Ye,jo$1]})}return n})();function wh(n,r){return this._trackRow(r)}var xl=(n,r)=>r.id;function kh(n,r){if(n&1&&(Vc$1(0,"tr",0)(1,"td",3),UD(2),Hc$1()()),n&2){let e=pD();Wv(),dh$1("padding-top",e._cellPadding)("padding-bottom",e._cellPadding),Qp("colspan",e.numCols),Wv(),Uc$1(" ",e.label," ");}}function Ch(n,r){if(n&1&&(Vc$1(0,"td",3),UD(1),Hc$1()),n&2){let e=pD(2);dh$1("padding-top",e._cellPadding)("padding-bottom",e._cellPadding),Qp("colspan",e._firstRowOffset),Wv(),Uc$1(" ",e._firstRowOffset>=e.labelMinRequiredCells?e.label:""," ");}}function Dh(n,r){if(n&1){let e=iD();Vc$1(0,"td",6)(1,"button",7),rh$1("click",function(i){let a=ku(e).$implicit,o=pD(2);return Ou(o._cellClicked(a,i))})("focus",function(i){let a=ku(e).$implicit,o=pD(2);return Ou(o._emitActiveDateChange(a,i))}),Vc$1(2,"span",8),UD(3),Hc$1(),Kp(4,"span",9),Hc$1()();}if(n&2){let e=r.$implicit,t=r.$index,i=pD().$index,a=pD();dh$1("width",a._cellWidth)("padding-top",a._cellPadding)("padding-bottom",a._cellPadding),Qp("data-mat-row",i)("data-mat-col",t),Wv(),RD(e.cssClasses),fh$1("mat-calendar-body-disabled",!e.enabled)("mat-calendar-body-active",a._isActiveCell(i,t))("mat-calendar-body-range-start",a._isRangeStart(e.compareValue))("mat-calendar-body-range-end",a._isRangeEnd(e.compareValue))("mat-calendar-body-in-range",a._isInRange(e.compareValue))("mat-calendar-body-comparison-bridge-start",a._isComparisonBridgeStart(e.compareValue,i,t))("mat-calendar-body-comparison-bridge-end",a._isComparisonBridgeEnd(e.compareValue,i,t))("mat-calendar-body-comparison-start",a._isComparisonStart(e.compareValue))("mat-calendar-body-comparison-end",a._isComparisonEnd(e.compareValue))("mat-calendar-body-in-comparison-range",a._isInComparisonRange(e.compareValue))("mat-calendar-body-preview-start",a._isPreviewStart(e.compareValue))("mat-calendar-body-preview-end",a._isPreviewEnd(e.compareValue))("mat-calendar-body-in-preview",a._isInPreview(e.compareValue)),eh$1("tabIndex",a._isActiveCell(i,t)?0:-1),Qp("aria-label",e.ariaLabel)("aria-disabled",!e.enabled||null)("aria-pressed",a._isSelected(e.compareValue))("aria-current",a.todayValue===e.compareValue?"date":null)("aria-describedby",a._getDescribedby(e.compareValue)),Wv(),fh$1("mat-calendar-body-selected",a._isSelected(e.compareValue))("mat-calendar-body-comparison-identical",a._isComparisonIdentical(e.compareValue))("mat-calendar-body-today",a.todayValue===e.compareValue),Wv(),Uc$1(" ",e.displayValue," ");}}function Mh(n,r){if(n&1&&(Vc$1(0,"tr",1),QE(1,Ch,2,6,"td",4),JE(2,Dh,5,49,"td",5,xl),Hc$1()),n&2){let e=r.$implicit,t=r.$index,i=pD();Wv(),ZE(t===0&&i._firstRowOffset?1:-1),Wv(),XE(e);}}function Sh(n,r){if(n&1&&(yi(0,"th",2)(1,"span",6),UD(2),jc$1(),yi(3,"span",3),UD(4),jc$1()()),n&2){let e=r.$implicit;Wv(2),Ih$1(e.long),Wv(2),Ih$1(e.narrow);}}var Ih=["*"];function Eh(n,r){}function Th(n,r){if(n&1){let e=iD();yi(0,"mat-month-view",4),Th$1("activeDateChange",function(i){ku(e);let a=pD();return zD(a.activeDate,i)||(a.activeDate=i),Ou(i)}),nh$1("_userSelection",function(i){ku(e);let a=pD();return Ou(a._dateSelected(i))})("dragStarted",function(i){ku(e);let a=pD();return Ou(a._dragStarted(i))})("dragEnded",function(i){ku(e);let a=pD();return Ou(a._dragEnded(i))}),jc$1();}if(n&2){let e=pD();wh$1("activeDate",e.activeDate),Zp("selected",e.selected)("dateFilter",e.dateFilter)("maxDate",e.maxDate)("minDate",e.minDate)("dateClass",e.dateClass)("comparisonStart",e.comparisonStart)("comparisonEnd",e.comparisonEnd)("startDateAccessibleName",e.startDateAccessibleName)("endDateAccessibleName",e.endDateAccessibleName)("activeDrag",e._activeDrag);}}function Ah(n,r){if(n&1){let e=iD();yi(0,"mat-year-view",5),Th$1("activeDateChange",function(i){ku(e);let a=pD();return zD(a.activeDate,i)||(a.activeDate=i),Ou(i)}),nh$1("monthSelected",function(i){ku(e);let a=pD();return Ou(a._monthSelectedInYearView(i))})("selectedChange",function(i){ku(e);let a=pD();return Ou(a._goToDateInView(i,"month"))}),jc$1();}if(n&2){let e=pD();wh$1("activeDate",e.activeDate),Zp("selected",e.selected)("dateFilter",e.dateFilter)("maxDate",e.maxDate)("minDate",e.minDate)("dateClass",e.dateClass);}}function Rh(n,r){if(n&1){let e=iD();yi(0,"mat-multi-year-view",6),Th$1("activeDateChange",function(i){ku(e);let a=pD();return zD(a.activeDate,i)||(a.activeDate=i),Ou(i)}),nh$1("yearSelected",function(i){ku(e);let a=pD();return Ou(a._yearSelectedInMultiYearView(i))})("selectedChange",function(i){ku(e);let a=pD();return Ou(a._goToDateInView(i,"year"))}),jc$1();}if(n&2){let e=pD();wh$1("activeDate",e.activeDate),Zp("selected",e.selected)("dateFilter",e.dateFilter)("maxDate",e.maxDate)("minDate",e.minDate)("dateClass",e.dateClass);}}function Fh(n,r){}var Oh=["button"],Ph=[[["","matDatepickerToggleIcon",""]]],Vh=["[matDatepickerToggleIcon]"];function Lh(n,r){n&1&&(Gu(),yi(0,"svg",2),Yp(1,"path",3),jc$1());}var bi=(()=>{class n{changes=new ne;calendarLabel="Calendar";openCalendarLabel="Open calendar";closeCalendarLabel="Close calendar";prevMonthLabel="Previous month";nextMonthLabel="Next month";prevYearLabel="Previous year";nextYearLabel="Next year";prevMultiYearLabel="Previous 24 years";nextMultiYearLabel="Next 24 years";switchToMonthViewLabel="Choose date";switchToMultiYearViewLabel="Choose month and year";startDateLabel="Start date";endDateLabel="End date";comparisonDateLabel="Comparison range";formatYearRange(e,t){return `${e} \u2013 ${t}`}formatYearRangeLabel(e,t){return `${e} to ${t}`}static \u0275fac=function(t){return new(t||n)};static \u0275prov=Er$1({token:n,factory:n.\u0275fac})}return n})(),Nh=0,Hi=class{value;displayValue;ariaLabel;enabled;compareValue;rawValue;id=Nh++;cssClasses;constructor(r,e,t,i,a,o=r,c){this.value=r,this.displayValue=e,this.ariaLabel=t,this.enabled=i,this.compareValue=o,this.rawValue=c,this.cssClasses=a instanceof Set?Array.from(a):a;}},Bh={passive:false,capture:true},Hn={passive:true,capture:true},fl={passive:true},gi=(()=>{class n{_elementRef=T(Dr$1);_ngZone=T(Se);_platform=T(D);_intl=T(bi);_eventCleanups;_skipNextFocus=false;_focusActiveCellAfterViewChecked=false;label;rows;todayValue;startValue;endValue;labelMinRequiredCells;numCols=7;activeCell=0;ngAfterViewChecked(){this._focusActiveCellAfterViewChecked&&(this._focusActiveCell(),this._focusActiveCellAfterViewChecked=false);}isRange=false;cellAspectRatio=1;comparisonStart=null;comparisonEnd=null;previewStart=null;previewEnd=null;startDateAccessibleName=null;endDateAccessibleName=null;selectedValueChange=new $e;previewChange=new $e;activeDateChange=new $e;dragStarted=new $e;dragEnded=new $e;_firstRowOffset;_cellPadding;_cellWidth;_startDateLabelId;_endDateLabelId;_comparisonStartDateLabelId;_comparisonEndDateLabelId;_didDragSinceMouseDown=false;_injector=T(ge);comparisonDateAccessibleName=this._intl.comparisonDateLabel;_trackRow=e=>e;constructor(){let e=T(MI),t=T(Y);this._startDateLabelId=t.getId("mat-calendar-body-start-"),this._endDateLabelId=t.getId("mat-calendar-body-end-"),this._comparisonStartDateLabelId=t.getId("mat-calendar-body-comparison-start-"),this._comparisonEndDateLabelId=t.getId("mat-calendar-body-comparison-end-"),T(W$1).load(bn$1),this._ngZone.runOutsideAngular(()=>{let i=this._elementRef.nativeElement,a=[e.listen(i,"touchmove",this._touchmoveHandler,Bh),e.listen(i,"mouseenter",this._enterHandler,Hn),e.listen(i,"focus",this._enterHandler,Hn),e.listen(i,"mouseleave",this._leaveHandler,Hn),e.listen(i,"blur",this._leaveHandler,Hn),e.listen(i,"mousedown",this._mousedownHandler,fl),e.listen(i,"touchstart",this._mousedownHandler,fl)];this._platform.isBrowser&&a.push(e.listen("window","mouseup",this._mouseupHandler),e.listen("window","touchend",this._touchendHandler)),this._eventCleanups=a;});}_cellClicked(e,t){this._didDragSinceMouseDown||e.enabled&&this.selectedValueChange.emit({value:e.value,event:t});}_emitActiveDateChange(e,t){e.enabled&&this.activeDateChange.emit({value:e.value,event:t});}_isSelected(e){return this.startValue===e||this.endValue===e}ngOnChanges(e){let t=e.numCols,{rows:i,numCols:a}=this;(e.rows||t)&&(this._firstRowOffset=i&&i.length&&i[0].length?a-i[0].length:0),(e.cellAspectRatio||t||!this._cellPadding)&&(this._cellPadding=`${50*this.cellAspectRatio/a}%`),(t||!this._cellWidth)&&(this._cellWidth=`${100/a}%`);}ngOnDestroy(){this._eventCleanups.forEach(e=>e());}_isActiveCell(e,t){let i=e*this.numCols+t;return e&&(i-=this._firstRowOffset),i==this.activeCell}_focusActiveCell(e=true){Dv(()=>{setTimeout(()=>{let t=this._elementRef.nativeElement.querySelector(".mat-calendar-body-active");t&&(e||(this._skipNextFocus=true),t.focus());});},{injector:this._injector});}_scheduleFocusActiveCellAfterViewChecked(){this._focusActiveCellAfterViewChecked=true;}_isRangeStart(e){return _r(e,this.startValue,this.endValue)}_isRangeEnd(e){return fr(e,this.startValue,this.endValue)}_isInRange(e){return gr(e,this.startValue,this.endValue,this.isRange)}_isComparisonStart(e){return _r(e,this.comparisonStart,this.comparisonEnd)}_isComparisonBridgeStart(e,t,i){if(!this._isComparisonStart(e)||this._isRangeStart(e)||!this._isInRange(e))return  false;let a=this.rows[t][i-1];if(!a){let o=this.rows[t-1];a=o&&o[o.length-1];}return a&&!this._isRangeEnd(a.compareValue)}_isComparisonBridgeEnd(e,t,i){if(!this._isComparisonEnd(e)||this._isRangeEnd(e)||!this._isInRange(e))return  false;let a=this.rows[t][i+1];if(!a){let o=this.rows[t+1];a=o&&o[0];}return a&&!this._isRangeStart(a.compareValue)}_isComparisonEnd(e){return fr(e,this.comparisonStart,this.comparisonEnd)}_isInComparisonRange(e){return gr(e,this.comparisonStart,this.comparisonEnd,this.isRange)}_isComparisonIdentical(e){return this.comparisonStart===this.comparisonEnd&&e===this.comparisonStart}_isPreviewStart(e){return _r(e,this.previewStart,this.previewEnd)}_isPreviewEnd(e){return fr(e,this.previewStart,this.previewEnd)}_isInPreview(e){return gr(e,this.previewStart,this.previewEnd,this.isRange)}_getDescribedby(e){if(!this.isRange)return null;if(this.startValue===e&&this.endValue===e)return `${this._startDateLabelId} ${this._endDateLabelId}`;if(this.startValue===e)return this._startDateLabelId;if(this.endValue===e)return this._endDateLabelId;if(this.comparisonStart!==null&&this.comparisonEnd!==null){if(e===this.comparisonStart&&e===this.comparisonEnd)return `${this._comparisonStartDateLabelId} ${this._comparisonEndDateLabelId}`;if(e===this.comparisonStart)return this._comparisonStartDateLabelId;if(e===this.comparisonEnd)return this._comparisonEndDateLabelId}return null}_enterHandler=e=>{if(this._skipNextFocus&&e.type==="focus"){this._skipNextFocus=false;return}if(e.target&&this.isRange){let t=this._getCellFromElement(e.target);t&&this._ngZone.run(()=>this.previewChange.emit({value:t.enabled?t:null,event:e}));}};_touchmoveHandler=e=>{if(!this.isRange)return;let t=gl(e),i=t?this._getCellFromElement(t):null;t!==e.target&&(this._didDragSinceMouseDown=true),ur(e.target)&&e.preventDefault(),this._ngZone.run(()=>this.previewChange.emit({value:i?.enabled?i:null,event:e}));};_leaveHandler=e=>{this.previewEnd!==null&&this.isRange&&(e.type!=="blur"&&(this._didDragSinceMouseDown=true),e.target&&this._getCellFromElement(e.target)&&!(e.relatedTarget&&this._getCellFromElement(e.relatedTarget))&&this._ngZone.run(()=>this.previewChange.emit({value:null,event:e})));};_mousedownHandler=e=>{if(!this.isRange)return;this._didDragSinceMouseDown=false;let t=e.target&&this._getCellFromElement(e.target);!t||!this._isInRange(t.compareValue)||this._ngZone.run(()=>{this.dragStarted.emit({value:t.rawValue,event:e});});};_mouseupHandler=e=>{if(!this.isRange)return;let t=ur(e.target);if(!t){this._ngZone.run(()=>{this.dragEnded.emit({value:null,event:e});});return}t.closest(".mat-calendar-body")===this._elementRef.nativeElement&&this._ngZone.run(()=>{let i=this._getCellFromElement(t);this.dragEnded.emit({value:i?.rawValue??null,event:e});});};_touchendHandler=e=>{let t=gl(e);t&&this._mouseupHandler({target:t});};_getCellFromElement(e){let t=ur(e);if(t){let i=t.getAttribute("data-mat-row"),a=t.getAttribute("data-mat-col");if(i&&a)return this.rows[parseInt(i)]?.[parseInt(a)]||null}return null}static \u0275fac=function(t){return new(t||n)};static \u0275cmp=TE({type:n,selectors:[["","mat-calendar-body",""]],hostAttrs:[1,"mat-calendar-body"],inputs:{label:"label",rows:"rows",todayValue:"todayValue",startValue:"startValue",endValue:"endValue",labelMinRequiredCells:"labelMinRequiredCells",numCols:"numCols",activeCell:"activeCell",isRange:"isRange",cellAspectRatio:"cellAspectRatio",comparisonStart:"comparisonStart",comparisonEnd:"comparisonEnd",previewStart:"previewStart",previewEnd:"previewEnd",startDateAccessibleName:"startDateAccessibleName",endDateAccessibleName:"endDateAccessibleName"},outputs:{selectedValueChange:"selectedValueChange",previewChange:"previewChange",activeDateChange:"activeDateChange",dragStarted:"dragStarted",dragEnded:"dragEnded"},exportAs:["matCalendarBody"],features:[zm$1],decls:11,vars:11,consts:[["aria-hidden","true"],["role","row"],[1,"mat-calendar-body-hidden-label",3,"id"],[1,"mat-calendar-body-label"],[1,"mat-calendar-body-label",3,"paddingTop","paddingBottom"],["role","gridcell",1,"mat-calendar-body-cell-container",3,"width","paddingTop","paddingBottom"],["role","gridcell",1,"mat-calendar-body-cell-container"],["type","button",1,"mat-calendar-body-cell",3,"click","focus","tabindex"],[1,"mat-calendar-body-cell-content","mat-focus-indicator"],["aria-hidden","true",1,"mat-calendar-body-cell-preview"]],template:function(t,i){t&1&&(QE(0,kh,3,6,"tr",0),JE(1,Mh,4,1,"tr",1,wh,true),Vc$1(3,"span",2),UD(4),Hc$1(),Vc$1(5,"span",2),UD(6),Hc$1(),Vc$1(7,"span",2),UD(8),Hc$1(),Vc$1(9,"span",2),UD(10),Hc$1()),t&2&&(ZE(i._firstRowOffset<i.labelMinRequiredCells?0:-1),Wv(),XE(i.rows),Wv(2),eh$1("id",i._startDateLabelId),Wv(),Uc$1(" ",i.startDateAccessibleName,`
`),Wv(),eh$1("id",i._endDateLabelId),Wv(),Uc$1(" ",i.endDateAccessibleName,`
`),Wv(),eh$1("id",i._comparisonStartDateLabelId),Wv(),Eh$1(" ",i.comparisonDateAccessibleName," ",i.startDateAccessibleName,`
`),Wv(),eh$1("id",i._comparisonEndDateLabelId),Wv(),Eh$1(" ",i.comparisonDateAccessibleName," ",i.endDateAccessibleName,`
`));},styles:[`.mat-calendar-body {
  min-width: 224px;
}

.mat-calendar-body-today:not(.mat-calendar-body-selected):not(.mat-calendar-body-comparison-identical) {
  border-color: var(--mat-datepicker-calendar-date-today-outline-color, var(--mat-sys-primary));
}

.mat-calendar-body-label {
  height: 0;
  line-height: 0;
  text-align: start;
  padding-left: 4.7142857143%;
  padding-right: 4.7142857143%;
  font-size: var(--mat-datepicker-calendar-body-label-text-size, var(--mat-sys-title-small-size));
  font-weight: var(--mat-datepicker-calendar-body-label-text-weight, var(--mat-sys-title-small-weight));
  color: var(--mat-datepicker-calendar-body-label-text-color, var(--mat-sys-on-surface));
}

.mat-calendar-body-hidden-label {
  display: none;
}

.mat-calendar-body-cell-container {
  position: relative;
  height: 0;
  line-height: 0;
}

.mat-calendar-body-cell {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: none;
  text-align: center;
  outline: none;
  margin: 0;
  font-family: var(--mat-datepicker-calendar-text-font, var(--mat-sys-body-medium-font));
  font-size: var(--mat-datepicker-calendar-text-size, var(--mat-sys-body-medium-size));
  -webkit-user-select: none;
  user-select: none;
  cursor: pointer;
  outline: none;
  border: none;
  -webkit-tap-highlight-color: transparent;
}
.mat-calendar-body-cell::-moz-focus-inner {
  border: 0;
}

.mat-calendar-body-cell::before,
.mat-calendar-body-cell::after,
.mat-calendar-body-cell-preview {
  content: "";
  position: absolute;
  top: 5%;
  left: 0;
  z-index: 0;
  box-sizing: border-box;
  display: block;
  height: 90%;
  width: 100%;
}

.mat-calendar-body-range-start:not(.mat-calendar-body-in-comparison-range)::before,
.mat-calendar-body-range-start::after,
.mat-calendar-body-comparison-start:not(.mat-calendar-body-comparison-bridge-start)::before,
.mat-calendar-body-comparison-start::after,
.mat-calendar-body-preview-start .mat-calendar-body-cell-preview {
  left: 5%;
  width: 95%;
  border-top-left-radius: 999px;
  border-bottom-left-radius: 999px;
}
[dir=rtl] .mat-calendar-body-range-start:not(.mat-calendar-body-in-comparison-range)::before,
[dir=rtl] .mat-calendar-body-range-start::after,
[dir=rtl] .mat-calendar-body-comparison-start:not(.mat-calendar-body-comparison-bridge-start)::before,
[dir=rtl] .mat-calendar-body-comparison-start::after,
[dir=rtl] .mat-calendar-body-preview-start .mat-calendar-body-cell-preview {
  left: 0;
  border-radius: 0;
  border-top-right-radius: 999px;
  border-bottom-right-radius: 999px;
}

.mat-calendar-body-range-end:not(.mat-calendar-body-in-comparison-range)::before,
.mat-calendar-body-range-end::after,
.mat-calendar-body-comparison-end:not(.mat-calendar-body-comparison-bridge-end)::before,
.mat-calendar-body-comparison-end::after,
.mat-calendar-body-preview-end .mat-calendar-body-cell-preview {
  width: 95%;
  border-top-right-radius: 999px;
  border-bottom-right-radius: 999px;
}
[dir=rtl] .mat-calendar-body-range-end:not(.mat-calendar-body-in-comparison-range)::before,
[dir=rtl] .mat-calendar-body-range-end::after,
[dir=rtl] .mat-calendar-body-comparison-end:not(.mat-calendar-body-comparison-bridge-end)::before,
[dir=rtl] .mat-calendar-body-comparison-end::after,
[dir=rtl] .mat-calendar-body-preview-end .mat-calendar-body-cell-preview {
  left: 5%;
  border-radius: 0;
  border-top-left-radius: 999px;
  border-bottom-left-radius: 999px;
}

[dir=rtl] .mat-calendar-body-comparison-bridge-start.mat-calendar-body-range-end::after,
[dir=rtl] .mat-calendar-body-comparison-bridge-end.mat-calendar-body-range-start::after {
  width: 95%;
  border-top-right-radius: 999px;
  border-bottom-right-radius: 999px;
}

.mat-calendar-body-comparison-start.mat-calendar-body-range-end::after, [dir=rtl] .mat-calendar-body-comparison-start.mat-calendar-body-range-end::after,
.mat-calendar-body-comparison-end.mat-calendar-body-range-start::after,
[dir=rtl] .mat-calendar-body-comparison-end.mat-calendar-body-range-start::after {
  width: 90%;
}

.mat-calendar-body-in-preview {
  color: var(--mat-datepicker-calendar-date-preview-state-outline-color, var(--mat-sys-primary));
}
.mat-calendar-body-in-preview .mat-calendar-body-cell-preview {
  border-top: dashed 1px;
  border-bottom: dashed 1px;
}

.mat-calendar-body-preview-start .mat-calendar-body-cell-preview {
  border-left: dashed 1px;
}
[dir=rtl] .mat-calendar-body-preview-start .mat-calendar-body-cell-preview {
  border-left: 0;
  border-right: dashed 1px;
}

.mat-calendar-body-preview-end .mat-calendar-body-cell-preview {
  border-right: dashed 1px;
}
[dir=rtl] .mat-calendar-body-preview-end .mat-calendar-body-cell-preview {
  border-right: 0;
  border-left: dashed 1px;
}

.mat-calendar-body-disabled {
  cursor: default;
}
.mat-calendar-body-disabled > .mat-calendar-body-cell-content:not(.mat-calendar-body-selected):not(.mat-calendar-body-comparison-identical) {
  color: var(--mat-datepicker-calendar-date-disabled-state-text-color, color-mix(in srgb, var(--mat-sys-on-surface) 38%, transparent));
}
.mat-calendar-body-disabled > .mat-calendar-body-today:not(.mat-calendar-body-selected):not(.mat-calendar-body-comparison-identical) {
  border-color: var(--mat-datepicker-calendar-date-today-disabled-state-outline-color, color-mix(in srgb, var(--mat-sys-on-surface) 38%, transparent));
}
@media (forced-colors: active) {
  .mat-calendar-body-disabled {
    opacity: 0.5;
  }
}

.mat-calendar-body-cell-content {
  top: 5%;
  left: 5%;
  z-index: 1;
  display: flex;
  align-items: center;
  justify-content: center;
  box-sizing: border-box;
  width: 90%;
  height: 90%;
  line-height: 1;
  border-width: 1px;
  border-style: solid;
  border-radius: 999px;
  color: var(--mat-datepicker-calendar-date-text-color, var(--mat-sys-on-surface));
  border-color: var(--mat-datepicker-calendar-date-outline-color, transparent);
}
.mat-calendar-body-cell-content.mat-focus-indicator {
  position: absolute;
}
@media (forced-colors: active) {
  .mat-calendar-body-cell-content {
    border: none;
  }
}

.cdk-keyboard-focused .mat-calendar-body-active > .mat-calendar-body-cell-content:not(.mat-calendar-body-selected):not(.mat-calendar-body-comparison-identical), .cdk-program-focused .mat-calendar-body-active > .mat-calendar-body-cell-content:not(.mat-calendar-body-selected):not(.mat-calendar-body-comparison-identical) {
  background-color: var(--mat-datepicker-calendar-date-focus-state-background-color, color-mix(in srgb, var(--mat-sys-on-surface) calc(var(--mat-sys-focus-state-layer-opacity) * 100%), transparent));
}

@media (hover: hover) {
  .mat-calendar-body-cell:not(.mat-calendar-body-disabled):hover > .mat-calendar-body-cell-content:not(.mat-calendar-body-selected):not(.mat-calendar-body-comparison-identical) {
    background-color: var(--mat-datepicker-calendar-date-hover-state-background-color, color-mix(in srgb, var(--mat-sys-on-surface) calc(var(--mat-sys-hover-state-layer-opacity) * 100%), transparent));
  }
}
.mat-calendar-body-selected {
  background-color: var(--mat-datepicker-calendar-date-selected-state-background-color, var(--mat-sys-primary));
  color: var(--mat-datepicker-calendar-date-selected-state-text-color, var(--mat-sys-on-primary));
}
.mat-calendar-body-disabled > .mat-calendar-body-selected {
  background-color: var(--mat-datepicker-calendar-date-selected-disabled-state-background-color, color-mix(in srgb, var(--mat-sys-on-surface) 38%, transparent));
}
.mat-calendar-body-selected.mat-calendar-body-today {
  box-shadow: inset 0 0 0 1px var(--mat-datepicker-calendar-date-today-selected-state-outline-color, var(--mat-sys-primary));
}

.mat-calendar-body-in-range::before {
  background: var(--mat-datepicker-calendar-date-in-range-state-background-color, var(--mat-sys-primary-container));
}

.mat-calendar-body-comparison-identical,
.mat-calendar-body-in-comparison-range::before {
  background: var(--mat-datepicker-calendar-date-in-comparison-range-state-background-color, var(--mat-sys-tertiary-container));
}

.mat-calendar-body-comparison-identical,
.mat-calendar-body-in-comparison-range::before {
  background: var(--mat-datepicker-calendar-date-in-comparison-range-state-background-color, var(--mat-sys-tertiary-container));
}

.mat-calendar-body-comparison-bridge-start::before,
[dir=rtl] .mat-calendar-body-comparison-bridge-end::before {
  background: linear-gradient(to right, var(--mat-datepicker-calendar-date-in-range-state-background-color, var(--mat-sys-primary-container)) 50%, var(--mat-datepicker-calendar-date-in-comparison-range-state-background-color, var(--mat-sys-tertiary-container)) 50%);
}

.mat-calendar-body-comparison-bridge-end::before,
[dir=rtl] .mat-calendar-body-comparison-bridge-start::before {
  background: linear-gradient(to left, var(--mat-datepicker-calendar-date-in-range-state-background-color, var(--mat-sys-primary-container)) 50%, var(--mat-datepicker-calendar-date-in-comparison-range-state-background-color, var(--mat-sys-tertiary-container)) 50%);
}

.mat-calendar-body-in-range > .mat-calendar-body-comparison-identical,
.mat-calendar-body-in-comparison-range.mat-calendar-body-in-range::after {
  background: var(--mat-datepicker-calendar-date-in-overlap-range-state-background-color, var(--mat-sys-secondary-container));
}

.mat-calendar-body-comparison-identical.mat-calendar-body-selected,
.mat-calendar-body-in-comparison-range > .mat-calendar-body-selected {
  background: var(--mat-datepicker-calendar-date-in-overlap-range-selected-state-background-color, var(--mat-sys-secondary));
}

@media (forced-colors: active) {
  .mat-datepicker-popup:not(:empty),
  .mat-calendar-body-cell:not(.mat-calendar-body-in-range) .mat-calendar-body-selected {
    outline: solid 1px;
  }
  .mat-calendar-body-today {
    outline: dotted 1px;
  }
  .mat-calendar-body-cell::before,
  .mat-calendar-body-cell::after,
  .mat-calendar-body-selected {
    background: none;
  }
  .mat-calendar-body-in-range::before,
  .mat-calendar-body-comparison-bridge-start::before,
  .mat-calendar-body-comparison-bridge-end::before {
    border-top: solid 1px;
    border-bottom: solid 1px;
  }
  .mat-calendar-body-range-start::before {
    border-left: solid 1px;
  }
  [dir=rtl] .mat-calendar-body-range-start::before {
    border-left: 0;
    border-right: solid 1px;
  }
  .mat-calendar-body-range-end::before {
    border-right: solid 1px;
  }
  [dir=rtl] .mat-calendar-body-range-end::before {
    border-right: 0;
    border-left: solid 1px;
  }
  .mat-calendar-body-in-comparison-range::before {
    border-top: dashed 1px;
    border-bottom: dashed 1px;
  }
  .mat-calendar-body-comparison-start::before {
    border-left: dashed 1px;
  }
  [dir=rtl] .mat-calendar-body-comparison-start::before {
    border-left: 0;
    border-right: dashed 1px;
  }
  .mat-calendar-body-comparison-end::before {
    border-right: dashed 1px;
  }
  [dir=rtl] .mat-calendar-body-comparison-end::before {
    border-right: 0;
    border-left: dashed 1px;
  }
}
`],encapsulation:2})}return n})();function pr(n){return n?.nodeName==="TD"}function ur(n){let r;return pr(n)?r=n:pr(n.parentNode)?r=n.parentNode:pr(n.parentNode?.parentNode)&&(r=n.parentNode.parentNode),r?.getAttribute("data-mat-row")!=null?r:null}function _r(n,r,e){return e!==null&&r!==e&&n<e&&n===r}function fr(n,r,e){return r!==null&&r!==e&&n>=r&&n===e}function gr(n,r,e,t){return t&&r!==null&&e!==null&&r!==e&&n>=r&&n<=e}function gl(n){let r=n.changedTouches[0];return document.elementFromPoint(r.clientX,r.clientY)}var ct=class{start;end;_disableStructuralEquivalency;constructor(r,e){this.start=r,this.end=e;}},jn=(()=>{class n{selection;_adapter;_selectionChanged=new ne;selectionChanged=this._selectionChanged;constructor(e,t){this.selection=e,this._adapter=t,this.selection=e;}updateSelection(e,t){let i=this.selection;this.selection=e,this._selectionChanged.next({selection:e,source:t,oldValue:i});}ngOnDestroy(){this._selectionChanged.complete();}_isValidDateInstance(e){return this._adapter.isDateInstance(e)&&this._adapter.isValid(e)}static \u0275fac=function(t){BI();};static \u0275prov=re({token:n,factory:n.\u0275fac})}return n})(),zh=(()=>{class n extends jn{constructor(e){super(null,e);}add(e){super.updateSelection(e,this);}isValid(){return this.selection!=null&&this._isValidDateInstance(this.selection)}isComplete(){return this.selection!=null}clone(){let e=new n(this._adapter);return e.updateSelection(this.selection,this),e}static \u0275fac=function(t){return new(t||n)(Me(Xe$1))};static \u0275prov=re({token:n,factory:n.\u0275fac})}return n})();var Hh={provide:jn,useFactory:()=>T(jn,{optional:true,skipSelf:true})||new zh(T(Xe$1))};var wl=new x("MAT_DATE_RANGE_SELECTION_STRATEGY");var br=7,jh=0,bl=(()=>{class n{_changeDetectorRef=T(L0);_dateFormats=T(Ur,{optional:true});_dateAdapter=T(Xe$1,{optional:true});_dir=T(Er$2,{optional:true});_rangeStrategy=T(wl,{optional:true});_rerenderSubscription=j.EMPTY;_selectionKeyPressed=false;get activeDate(){return this._activeDate}set activeDate(e){let t=this._activeDate,i=this._dateAdapter.getValidDateOrNull(this._dateAdapter.deserialize(e))||this._dateAdapter.today();this._activeDate=this._dateAdapter.clampDate(i,this.minDate,this.maxDate),this._hasSameMonthAndYear(t,this._activeDate)||this._init();}_activeDate;get selected(){return this._selected}set selected(e){e instanceof ct?this._selected=e:this._selected=this._dateAdapter.getValidDateOrNull(this._dateAdapter.deserialize(e)),this._setRanges(this._selected);}_selected=null;get minDate(){return this._minDate}set minDate(e){this._minDate=this._dateAdapter.getValidDateOrNull(this._dateAdapter.deserialize(e));}_minDate=null;get maxDate(){return this._maxDate}set maxDate(e){this._maxDate=this._dateAdapter.getValidDateOrNull(this._dateAdapter.deserialize(e));}_maxDate=null;dateFilter;dateClass;comparisonStart=null;comparisonEnd=null;startDateAccessibleName=null;endDateAccessibleName=null;activeDrag=null;selectedChange=new $e;_userSelection=new $e;dragStarted=new $e;dragEnded=new $e;activeDateChange=new $e;_matCalendarBody;_monthLabel=Vo$1("");_weeks=Vo$1([]);_firstWeekOffset=Vo$1(0);_rangeStart=Vo$1(null);_rangeEnd=Vo$1(null);_comparisonRangeStart=Vo$1(null);_comparisonRangeEnd=Vo$1(null);_previewStart=Vo$1(null);_previewEnd=Vo$1(null);_isRange=Vo$1(false);_todayDate=Vo$1(null);_weekdays=Vo$1([]);constructor(){T(W$1).load(ce),this._activeDate=this._dateAdapter.today();}ngAfterContentInit(){this._rerenderSubscription=this._dateAdapter.localeChanges.pipe(Hl$1(null)).subscribe(()=>this._init());}ngOnChanges(e){let t=e.comparisonStart||e.comparisonEnd;t&&!t.firstChange&&this._setRanges(this.selected),e.activeDrag&&!this.activeDrag&&this._clearPreview();}ngOnDestroy(){this._rerenderSubscription.unsubscribe();}_dateSelected(e){let t=e.value,i=this._getDateFromDayOfMonth(t),a,o;this._selected instanceof ct?(a=this._getDateInCurrentMonth(this._selected.start),o=this._getDateInCurrentMonth(this._selected.end)):a=o=this._getDateInCurrentMonth(this._selected),(a!==t||o!==t)&&this.selectedChange.emit(i),this._userSelection.emit({value:i,event:e.event}),this._clearPreview(),this._changeDetectorRef.markForCheck();}_updateActiveDate(e){let t=e.value,i=this._activeDate;this.activeDate=this._getDateFromDayOfMonth(t),this._dateAdapter.compareDate(i,this.activeDate)&&this.activeDateChange.emit(this._activeDate);}_handleCalendarBodyKeydown(e){let t=this._activeDate,i=this._isRtl();switch(e.keyCode){case 37:this.activeDate=this._dateAdapter.addCalendarDays(this._activeDate,i?1:-1);break;case 39:this.activeDate=this._dateAdapter.addCalendarDays(this._activeDate,i?-1:1);break;case 38:this.activeDate=this._dateAdapter.addCalendarDays(this._activeDate,-7);break;case 40:this.activeDate=this._dateAdapter.addCalendarDays(this._activeDate,7);break;case 36:this.activeDate=this._dateAdapter.addCalendarDays(this._activeDate,1-this._dateAdapter.getDate(this._activeDate));break;case 35:this.activeDate=this._dateAdapter.addCalendarDays(this._activeDate,this._dateAdapter.getNumDaysInMonth(this._activeDate)-this._dateAdapter.getDate(this._activeDate));break;case 33:this.activeDate=e.altKey?this._dateAdapter.addCalendarYears(this._activeDate,-1):this._dateAdapter.addCalendarMonths(this._activeDate,-1);break;case 34:this.activeDate=e.altKey?this._dateAdapter.addCalendarYears(this._activeDate,1):this._dateAdapter.addCalendarMonths(this._activeDate,1);break;case 13:case 32:this._selectionKeyPressed=true,this._canSelect(this._activeDate)&&e.preventDefault();return;case 27:this._previewEnd()!=null&&!$(e)&&(this._clearPreview(),this.activeDrag?this.dragEnded.emit({value:null,event:e}):(this.selectedChange.emit(null),this._userSelection.emit({value:null,event:e})),e.preventDefault(),e.stopPropagation());return;default:return}this._dateAdapter.compareDate(t,this.activeDate)&&(this.activeDateChange.emit(this.activeDate),this._focusActiveCellAfterViewChecked()),e.preventDefault();}_handleCalendarBodyKeyup(e){(e.keyCode===32||e.keyCode===13)&&(this._selectionKeyPressed&&this._canSelect(this._activeDate)&&this._dateSelected({value:this._dateAdapter.getDate(this._activeDate),event:e}),this._selectionKeyPressed=false);}_init(){this._setRanges(this.selected),this._todayDate.set(this._getCellCompareValue(this._dateAdapter.today())),this._monthLabel.set(this._dateFormats.display.monthLabel?this._dateAdapter.format(this.activeDate,this._dateFormats.display.monthLabel):this._dateAdapter.getMonthNames("short")[this._dateAdapter.getMonth(this.activeDate)].toLocaleUpperCase());let e=this._dateAdapter.createDate(this._dateAdapter.getYear(this.activeDate),this._dateAdapter.getMonth(this.activeDate),1);this._firstWeekOffset.set((br+this._dateAdapter.getDayOfWeek(e)-this._dateAdapter.getFirstDayOfWeek())%br),this._initWeekdays(),this._createWeekCells(),this._changeDetectorRef.markForCheck();}_focusActiveCell(e){this._matCalendarBody._focusActiveCell(e);}_focusActiveCellAfterViewChecked(){this._matCalendarBody._scheduleFocusActiveCellAfterViewChecked();}_previewChanged({event:e,value:t}){if(this._rangeStrategy){let i=t?t.rawValue:null,a=this._rangeStrategy.createPreview(i,this.selected,e);if(this._previewStart.set(this._getCellCompareValue(a.start)),this._previewEnd.set(this._getCellCompareValue(a.end)),this.activeDrag&&i){let o=this._rangeStrategy.createDrag?.(this.activeDrag.value,this.selected,i,e);o&&(this._previewStart.set(this._getCellCompareValue(o.start)),this._previewEnd.set(this._getCellCompareValue(o.end)));}}}_dragEnded(e){if(this.activeDrag)if(e.value){let t=this._rangeStrategy?.createDrag?.(this.activeDrag.value,this.selected,e.value,e.event);this.dragEnded.emit({value:t??null,event:e.event});}else this.dragEnded.emit({value:null,event:e.event});}_getDateFromDayOfMonth(e){return this._dateAdapter.createDate(this._dateAdapter.getYear(this.activeDate),this._dateAdapter.getMonth(this.activeDate),e)}_initWeekdays(){let e=this._dateAdapter.getFirstDayOfWeek(),t=this._dateAdapter.getDayOfWeekNames("narrow"),a=this._dateAdapter.getDayOfWeekNames("long").map((o,c)=>({long:o,narrow:t[c],id:jh++}));this._weekdays.set(a.slice(e).concat(a.slice(0,e)));}_createWeekCells(){let e=this._dateAdapter.getNumDaysInMonth(this.activeDate),t=this._dateAdapter.getDateNames(),i=[[]];for(let a=0,o=this._firstWeekOffset();a<e;a++,o++){o==br&&(i.push([]),o=0);let c=this._dateAdapter.createDate(this._dateAdapter.getYear(this.activeDate),this._dateAdapter.getMonth(this.activeDate),a+1),y=this._shouldEnableDate(c),F=this._dateAdapter.format(c,this._dateFormats.display.dateA11yLabel),H=this.dateClass?this.dateClass(c,"month"):void 0;i[i.length-1].push(new Hi(a+1,t[a],F,y,H,this._getCellCompareValue(c),c));}this._weeks.set(i);}_shouldEnableDate(e){return !!e&&(!this.minDate||this._dateAdapter.compareDate(e,this.minDate)>=0)&&(!this.maxDate||this._dateAdapter.compareDate(e,this.maxDate)<=0)&&(!this.dateFilter||this.dateFilter(e))}_getDateInCurrentMonth(e){return e&&this._hasSameMonthAndYear(e,this.activeDate)?this._dateAdapter.getDate(e):null}_hasSameMonthAndYear(e,t){return !!(e&&t&&this._dateAdapter.getMonth(e)==this._dateAdapter.getMonth(t)&&this._dateAdapter.getYear(e)==this._dateAdapter.getYear(t))}_getCellCompareValue(e){if(e){let t=this._dateAdapter.getYear(e),i=this._dateAdapter.getMonth(e),a=this._dateAdapter.getDate(e);return new Date(t,i,a).getTime()}return null}_isRtl(){return this._dir&&this._dir.value==="rtl"}_setRanges(e){e instanceof ct?(this._rangeStart.set(this._getCellCompareValue(e.start)),this._rangeEnd.set(this._getCellCompareValue(e.end)),this._isRange.set(true)):(this._rangeStart.set(this._getCellCompareValue(e)),this._rangeEnd.set(this._rangeStart()),this._isRange.set(false)),this._comparisonRangeStart.set(this._getCellCompareValue(this.comparisonStart)),this._comparisonRangeEnd.set(this._getCellCompareValue(this.comparisonEnd));}_canSelect(e){return !this.dateFilter||this.dateFilter(e)}_clearPreview(){this._previewStart.set(null),this._previewEnd.set(null);}static \u0275fac=function(t){return new(t||n)};static \u0275cmp=TE({type:n,selectors:[["mat-month-view"]],viewQuery:function(t,i){if(t&1&&sh$1(gi,5),t&2){let a;vD(a=ID())&&(i._matCalendarBody=a.first);}},inputs:{activeDate:"activeDate",selected:"selected",minDate:"minDate",maxDate:"maxDate",dateFilter:"dateFilter",dateClass:"dateClass",comparisonStart:"comparisonStart",comparisonEnd:"comparisonEnd",startDateAccessibleName:"startDateAccessibleName",endDateAccessibleName:"endDateAccessibleName",activeDrag:"activeDrag"},outputs:{selectedChange:"selectedChange",_userSelection:"_userSelection",dragStarted:"dragStarted",dragEnded:"dragEnded",activeDateChange:"activeDateChange"},exportAs:["matMonthView"],features:[zm$1],decls:8,vars:14,consts:[["role","grid",1,"mat-calendar-table"],[1,"mat-calendar-table-header"],["scope","col"],["aria-hidden","true"],["colspan","7",1,"mat-calendar-table-header-divider"],["mat-calendar-body","",3,"selectedValueChange","activeDateChange","previewChange","dragStarted","dragEnded","keyup","keydown","label","rows","todayValue","startValue","endValue","comparisonStart","comparisonEnd","previewStart","previewEnd","isRange","labelMinRequiredCells","activeCell","startDateAccessibleName","endDateAccessibleName"],[1,"cdk-visually-hidden"]],template:function(t,i){t&1&&(yi(0,"table",0)(1,"thead",1)(2,"tr"),JE(3,Sh,5,2,"th",2,xl),jc$1(),yi(5,"tr",3),Yp(6,"th",4),jc$1()(),yi(7,"tbody",5),nh$1("selectedValueChange",function(o){return i._dateSelected(o)})("activeDateChange",function(o){return i._updateActiveDate(o)})("previewChange",function(o){return i._previewChanged(o)})("dragStarted",function(o){return i.dragStarted.emit(o)})("dragEnded",function(o){return i._dragEnded(o)})("keyup",function(o){return i._handleCalendarBodyKeyup(o)})("keydown",function(o){return i._handleCalendarBodyKeydown(o)}),jc$1()()),t&2&&(Wv(3),XE(i._weekdays()),Wv(4),Zp("label",i._monthLabel())("rows",i._weeks())("todayValue",i._todayDate())("startValue",i._rangeStart())("endValue",i._rangeEnd())("comparisonStart",i._comparisonRangeStart())("comparisonEnd",i._comparisonRangeEnd())("previewStart",i._previewStart())("previewEnd",i._previewEnd())("isRange",i._isRange())("labelMinRequiredCells",3)("activeCell",i._dateAdapter.getDate(i.activeDate)-1)("startDateAccessibleName",i.startDateAccessibleName)("endDateAccessibleName",i.endDateAccessibleName));},dependencies:[gi],encapsulation:2})}return n})(),at=24,vr=4,vl=(()=>{class n{_changeDetectorRef=T(L0);_dateAdapter=T(Xe$1,{optional:true});_dir=T(Er$2,{optional:true});_rerenderSubscription=j.EMPTY;_selectionKeyPressed=false;get activeDate(){return this._activeDate}set activeDate(e){let t=this._activeDate,i=this._dateAdapter.getValidDateOrNull(this._dateAdapter.deserialize(e))||this._dateAdapter.today();this._activeDate=this._dateAdapter.clampDate(i,this.minDate,this.maxDate),kl(this._dateAdapter,t,this._activeDate,this.minDate,this.maxDate)||this._init();}_activeDate;get selected(){return this._selected}set selected(e){e instanceof ct?this._selected=e:this._selected=this._dateAdapter.getValidDateOrNull(this._dateAdapter.deserialize(e)),this._setSelectedYear(e);}_selected=null;get minDate(){return this._minDate}set minDate(e){this._minDate=this._dateAdapter.getValidDateOrNull(this._dateAdapter.deserialize(e));}_minDate=null;get maxDate(){return this._maxDate}set maxDate(e){this._maxDate=this._dateAdapter.getValidDateOrNull(this._dateAdapter.deserialize(e));}_maxDate=null;dateFilter;dateClass;selectedChange=new $e;yearSelected=new $e;activeDateChange=new $e;_matCalendarBody;_years=Vo$1([]);_todayYear=Vo$1(0);_selectedYear=Vo$1(null);constructor(){this._dateAdapter,this._activeDate=this._dateAdapter.today();}ngAfterContentInit(){this._rerenderSubscription=this._dateAdapter.localeChanges.pipe(Hl$1(null)).subscribe(()=>this._init());}ngOnDestroy(){this._rerenderSubscription.unsubscribe();}_init(){this._todayYear.set(this._dateAdapter.getYear(this._dateAdapter.today()));let t=this._dateAdapter.getYear(this._activeDate)-zi(this._dateAdapter,this.activeDate,this.minDate,this.maxDate),i=[];for(let a=0,o=[];a<at;a++)o.push(t+a),o.length==vr&&(i.push(o.map(c=>this._createCellForYear(c))),o=[]);this._years.set(i),this._changeDetectorRef.markForCheck();}_yearSelected(e){let t=e.value,i=this._dateAdapter.createDate(t,0,1),a=this._getDateFromYear(t);this.yearSelected.emit(i),this.selectedChange.emit(a);}_updateActiveDate(e){let t=e.value,i=this._activeDate;this.activeDate=this._getDateFromYear(t),this._dateAdapter.compareDate(i,this.activeDate)&&this.activeDateChange.emit(this.activeDate);}_handleCalendarBodyKeydown(e){let t=this._activeDate,i=this._isRtl();switch(e.keyCode){case 37:this.activeDate=this._dateAdapter.addCalendarYears(this._activeDate,i?1:-1);break;case 39:this.activeDate=this._dateAdapter.addCalendarYears(this._activeDate,i?-1:1);break;case 38:this.activeDate=this._dateAdapter.addCalendarYears(this._activeDate,-vr);break;case 40:this.activeDate=this._dateAdapter.addCalendarYears(this._activeDate,vr);break;case 36:this.activeDate=this._dateAdapter.addCalendarYears(this._activeDate,-zi(this._dateAdapter,this.activeDate,this.minDate,this.maxDate));break;case 35:this.activeDate=this._dateAdapter.addCalendarYears(this._activeDate,at-zi(this._dateAdapter,this.activeDate,this.minDate,this.maxDate)-1);break;case 33:this.activeDate=this._dateAdapter.addCalendarYears(this._activeDate,e.altKey?-at*10:-at);break;case 34:this.activeDate=this._dateAdapter.addCalendarYears(this._activeDate,e.altKey?at*10:at);break;case 13:case 32:this._selectionKeyPressed=true;break;default:return}this._dateAdapter.compareDate(t,this.activeDate)&&this.activeDateChange.emit(this.activeDate),this._focusActiveCellAfterViewChecked(),e.preventDefault();}_handleCalendarBodyKeyup(e){(e.keyCode===32||e.keyCode===13)&&(this._selectionKeyPressed&&this._yearSelected({value:this._dateAdapter.getYear(this._activeDate),event:e}),this._selectionKeyPressed=false);}_getActiveCell(){return zi(this._dateAdapter,this.activeDate,this.minDate,this.maxDate)}_focusActiveCell(){this._matCalendarBody._focusActiveCell();}_focusActiveCellAfterViewChecked(){this._matCalendarBody._scheduleFocusActiveCellAfterViewChecked();}_getDateFromYear(e){let t=this._dateAdapter.getMonth(this.activeDate),i=this._dateAdapter.getNumDaysInMonth(this._dateAdapter.createDate(e,t,1));return this._dateAdapter.createDate(e,t,Math.min(this._dateAdapter.getDate(this.activeDate),i))}_createCellForYear(e){let t=this._dateAdapter.createDate(e,0,1),i=this._dateAdapter.getYearName(t),a=this.dateClass?this.dateClass(t,"multi-year"):void 0;return new Hi(e,i,i,this._shouldEnableYear(e),a)}_shouldEnableYear(e){if(e==null||this.maxDate&&e>this._dateAdapter.getYear(this.maxDate)||this.minDate&&e<this._dateAdapter.getYear(this.minDate))return  false;if(!this.dateFilter)return  true;let t=this._dateAdapter.createDate(e,0,1);for(let i=t;this._dateAdapter.getYear(i)==e;i=this._dateAdapter.addCalendarDays(i,1))if(this.dateFilter(i))return  true;return  false}_isRtl(){return this._dir&&this._dir.value==="rtl"}_setSelectedYear(e){if(this._selectedYear.set(null),e instanceof ct){let t=e.start||e.end;t&&this._selectedYear.set(this._dateAdapter.getYear(t));}else e&&this._selectedYear.set(this._dateAdapter.getYear(e));}static \u0275fac=function(t){return new(t||n)};static \u0275cmp=TE({type:n,selectors:[["mat-multi-year-view"]],viewQuery:function(t,i){if(t&1&&sh$1(gi,5),t&2){let a;vD(a=ID())&&(i._matCalendarBody=a.first);}},inputs:{activeDate:"activeDate",selected:"selected",minDate:"minDate",maxDate:"maxDate",dateFilter:"dateFilter",dateClass:"dateClass"},outputs:{selectedChange:"selectedChange",yearSelected:"yearSelected",activeDateChange:"activeDateChange"},exportAs:["matMultiYearView"],decls:5,vars:7,consts:[["role","grid",1,"mat-calendar-table"],["aria-hidden","true",1,"mat-calendar-table-header"],["colspan","4",1,"mat-calendar-table-header-divider"],["mat-calendar-body","",3,"selectedValueChange","activeDateChange","keyup","keydown","rows","todayValue","startValue","endValue","numCols","cellAspectRatio","activeCell"]],template:function(t,i){t&1&&(yi(0,"table",0)(1,"thead",1)(2,"tr"),Yp(3,"th",2),jc$1()(),yi(4,"tbody",3),nh$1("selectedValueChange",function(o){return i._yearSelected(o)})("activeDateChange",function(o){return i._updateActiveDate(o)})("keyup",function(o){return i._handleCalendarBodyKeyup(o)})("keydown",function(o){return i._handleCalendarBodyKeydown(o)}),jc$1()()),t&2&&(Wv(4),Zp("rows",i._years())("todayValue",i._todayYear())("startValue",i._selectedYear())("endValue",i._selectedYear())("numCols",4)("cellAspectRatio",4/7)("activeCell",i._getActiveCell()));},dependencies:[gi],encapsulation:2})}return n})();function kl(n,r,e,t,i){let a=n.getYear(r),o=n.getYear(e),c=Cl(n,t,i);return Math.floor((a-c)/at)===Math.floor((o-c)/at)}function zi(n,r,e,t){let i=n.getYear(r);return Gh(i-Cl(n,e,t),at)}function Cl(n,r,e){let t=0;return e?t=n.getYear(e)-at+1:r&&(t=n.getYear(r)),t}function Gh(n,r){return (n%r+r)%r}var yl=(()=>{class n{_changeDetectorRef=T(L0);_dateFormats=T(Ur,{optional:true});_dateAdapter=T(Xe$1,{optional:true});_dir=T(Er$2,{optional:true});_rerenderSubscription=j.EMPTY;_selectionKeyPressed=false;get activeDate(){return this._activeDate}set activeDate(e){let t=this._activeDate,i=this._dateAdapter.getValidDateOrNull(this._dateAdapter.deserialize(e))||this._dateAdapter.today();this._activeDate=this._dateAdapter.clampDate(i,this.minDate,this.maxDate),this._dateAdapter.getYear(t)!==this._dateAdapter.getYear(this._activeDate)&&this._init();}_activeDate;get selected(){return this._selected}set selected(e){e instanceof ct?this._selected=e:this._selected=this._dateAdapter.getValidDateOrNull(this._dateAdapter.deserialize(e)),this._setSelectedMonth(e);}_selected=null;get minDate(){return this._minDate}set minDate(e){this._minDate=this._dateAdapter.getValidDateOrNull(this._dateAdapter.deserialize(e));}_minDate=null;get maxDate(){return this._maxDate}set maxDate(e){this._maxDate=this._dateAdapter.getValidDateOrNull(this._dateAdapter.deserialize(e));}_maxDate=null;dateFilter;dateClass;selectedChange=new $e;monthSelected=new $e;activeDateChange=new $e;_matCalendarBody;_months=Vo$1([]);_yearLabel=Vo$1("");_todayMonth=Vo$1(null);_selectedMonth=Vo$1(null);constructor(){this._activeDate=this._dateAdapter.today();}ngAfterContentInit(){this._rerenderSubscription=this._dateAdapter.localeChanges.pipe(Hl$1(null)).subscribe(()=>this._init());}ngOnDestroy(){this._rerenderSubscription.unsubscribe();}_monthSelected(e){let t=e.value,i=this._dateAdapter.createDate(this._dateAdapter.getYear(this.activeDate),t,1);this.monthSelected.emit(i);let a=this._getDateFromMonth(t);this.selectedChange.emit(a);}_updateActiveDate(e){let t=e.value,i=this._activeDate;this.activeDate=this._getDateFromMonth(t),this._dateAdapter.compareDate(i,this.activeDate)&&this.activeDateChange.emit(this.activeDate);}_handleCalendarBodyKeydown(e){let t=this._activeDate,i=this._isRtl();switch(e.keyCode){case 37:this.activeDate=this._dateAdapter.addCalendarMonths(this._activeDate,i?1:-1);break;case 39:this.activeDate=this._dateAdapter.addCalendarMonths(this._activeDate,i?-1:1);break;case 38:this.activeDate=this._dateAdapter.addCalendarMonths(this._activeDate,-4);break;case 40:this.activeDate=this._dateAdapter.addCalendarMonths(this._activeDate,4);break;case 36:this.activeDate=this._dateAdapter.addCalendarMonths(this._activeDate,-this._dateAdapter.getMonth(this._activeDate));break;case 35:this.activeDate=this._dateAdapter.addCalendarMonths(this._activeDate,11-this._dateAdapter.getMonth(this._activeDate));break;case 33:this.activeDate=this._dateAdapter.addCalendarYears(this._activeDate,e.altKey?-10:-1);break;case 34:this.activeDate=this._dateAdapter.addCalendarYears(this._activeDate,e.altKey?10:1);break;case 13:case 32:this._selectionKeyPressed=true;break;default:return}this._dateAdapter.compareDate(t,this.activeDate)&&(this.activeDateChange.emit(this.activeDate),this._focusActiveCellAfterViewChecked()),e.preventDefault();}_handleCalendarBodyKeyup(e){(e.keyCode===32||e.keyCode===13)&&(this._selectionKeyPressed&&this._monthSelected({value:this._dateAdapter.getMonth(this._activeDate),event:e}),this._selectionKeyPressed=false);}_init(){this._setSelectedMonth(this.selected),this._todayMonth.set(this._getMonthInCurrentYear(this._dateAdapter.today())),this._yearLabel.set(this._dateAdapter.getYearName(this.activeDate));let e=this._dateAdapter.getMonthNames("short");this._months.set([[0,1,2,3],[4,5,6,7],[8,9,10,11]].map(t=>t.map(i=>this._createCellForMonth(i,e[i])))),this._changeDetectorRef.markForCheck();}_focusActiveCell(){this._matCalendarBody._focusActiveCell();}_focusActiveCellAfterViewChecked(){this._matCalendarBody._scheduleFocusActiveCellAfterViewChecked();}_getMonthInCurrentYear(e){return e&&this._dateAdapter.getYear(e)==this._dateAdapter.getYear(this.activeDate)?this._dateAdapter.getMonth(e):null}_getDateFromMonth(e){let t=this._dateAdapter.createDate(this._dateAdapter.getYear(this.activeDate),e,1),i=this._dateAdapter.getNumDaysInMonth(t);return this._dateAdapter.createDate(this._dateAdapter.getYear(this.activeDate),e,Math.min(this._dateAdapter.getDate(this.activeDate),i))}_createCellForMonth(e,t){let i=this._dateAdapter.createDate(this._dateAdapter.getYear(this.activeDate),e,1),a=this._dateAdapter.format(i,this._dateFormats.display.monthYearA11yLabel),o=this.dateClass?this.dateClass(i,"year"):void 0;return new Hi(e,t.toLocaleUpperCase(),a,this._shouldEnableMonth(e),o)}_shouldEnableMonth(e){let t=this._dateAdapter.getYear(this.activeDate);if(e==null||this._isYearAndMonthAfterMaxDate(t,e)||this._isYearAndMonthBeforeMinDate(t,e))return  false;if(!this.dateFilter)return  true;let i=this._dateAdapter.createDate(t,e,1);for(let a=i;this._dateAdapter.getMonth(a)==e;a=this._dateAdapter.addCalendarDays(a,1))if(this.dateFilter(a))return  true;return  false}_isYearAndMonthAfterMaxDate(e,t){if(this.maxDate){let i=this._dateAdapter.getYear(this.maxDate),a=this._dateAdapter.getMonth(this.maxDate);return e>i||e===i&&t>a}return  false}_isYearAndMonthBeforeMinDate(e,t){if(this.minDate){let i=this._dateAdapter.getYear(this.minDate),a=this._dateAdapter.getMonth(this.minDate);return e<i||e===i&&t<a}return  false}_isRtl(){return this._dir&&this._dir.value==="rtl"}_setSelectedMonth(e){e instanceof ct?this._selectedMonth.set(this._getMonthInCurrentYear(e.start)||this._getMonthInCurrentYear(e.end)):this._selectedMonth.set(this._getMonthInCurrentYear(e));}static \u0275fac=function(t){return new(t||n)};static \u0275cmp=TE({type:n,selectors:[["mat-year-view"]],viewQuery:function(t,i){if(t&1&&sh$1(gi,5),t&2){let a;vD(a=ID())&&(i._matCalendarBody=a.first);}},inputs:{activeDate:"activeDate",selected:"selected",minDate:"minDate",maxDate:"maxDate",dateFilter:"dateFilter",dateClass:"dateClass"},outputs:{selectedChange:"selectedChange",monthSelected:"monthSelected",activeDateChange:"activeDateChange"},exportAs:["matYearView"],decls:5,vars:9,consts:[["role","grid",1,"mat-calendar-table"],["aria-hidden","true",1,"mat-calendar-table-header"],["colspan","4",1,"mat-calendar-table-header-divider"],["mat-calendar-body","",3,"selectedValueChange","activeDateChange","keyup","keydown","label","rows","todayValue","startValue","endValue","labelMinRequiredCells","numCols","cellAspectRatio","activeCell"]],template:function(t,i){t&1&&(yi(0,"table",0)(1,"thead",1)(2,"tr"),Yp(3,"th",2),jc$1()(),yi(4,"tbody",3),nh$1("selectedValueChange",function(o){return i._monthSelected(o)})("activeDateChange",function(o){return i._updateActiveDate(o)})("keyup",function(o){return i._handleCalendarBodyKeyup(o)})("keydown",function(o){return i._handleCalendarBodyKeydown(o)}),jc$1()()),t&2&&(Wv(4),Zp("label",i._yearLabel())("rows",i._months())("todayValue",i._todayMonth())("startValue",i._selectedMonth())("endValue",i._selectedMonth())("labelMinRequiredCells",2)("numCols",4)("cellAspectRatio",4/7)("activeCell",i._dateAdapter.getMonth(i.activeDate)));},dependencies:[gi],encapsulation:2})}return n})(),Dl=(()=>{class n{_intl=T(bi);calendar=T(yr);_dateAdapter=T(Xe$1,{optional:true});_dateFormats=T(Ur,{optional:true});_periodButtonText;_periodButtonDescription;_periodButtonLabel;_prevButtonLabel;_nextButtonLabel;constructor(){T(W$1).load(ce);let e=T(L0);this._updateLabels(),this.calendar.stateChanges.subscribe(()=>{this._updateLabels(),e.markForCheck();});}get periodButtonText(){return this._periodButtonText}get periodButtonDescription(){return this._periodButtonDescription}get periodButtonLabel(){return this._periodButtonLabel}get prevButtonLabel(){return this._prevButtonLabel}get nextButtonLabel(){return this._nextButtonLabel}currentPeriodClicked(){this.calendar.currentView=this.calendar.currentView=="month"?"multi-year":"month";}previousClicked(){this.previousEnabled()&&(this.calendar.activeDate=this.calendar.currentView=="month"?this._dateAdapter.addCalendarMonths(this.calendar.activeDate,-1):this._dateAdapter.addCalendarYears(this.calendar.activeDate,this.calendar.currentView=="year"?-1:-at));}nextClicked(){this.nextEnabled()&&(this.calendar.activeDate=this.calendar.currentView=="month"?this._dateAdapter.addCalendarMonths(this.calendar.activeDate,1):this._dateAdapter.addCalendarYears(this.calendar.activeDate,this.calendar.currentView=="year"?1:at));}previousEnabled(){return this.calendar.minDate?!this.calendar.minDate||!this._isSameView(this.calendar.activeDate,this.calendar.minDate):true}nextEnabled(){return !this.calendar.maxDate||!this._isSameView(this.calendar.activeDate,this.calendar.maxDate)}_updateLabels(){let e=this.calendar,t=this._intl,i=this._dateAdapter;e.currentView==="month"?(this._periodButtonText=i.format(e.activeDate,this._dateFormats.display.monthYearLabel).toLocaleUpperCase(),this._periodButtonDescription=i.format(e.activeDate,this._dateFormats.display.monthYearLabel).toLocaleUpperCase(),this._periodButtonLabel=t.switchToMultiYearViewLabel,this._prevButtonLabel=t.prevMonthLabel,this._nextButtonLabel=t.nextMonthLabel):e.currentView==="year"?(this._periodButtonText=i.getYearName(e.activeDate),this._periodButtonDescription=i.getYearName(e.activeDate),this._periodButtonLabel=t.switchToMonthViewLabel,this._prevButtonLabel=t.prevYearLabel,this._nextButtonLabel=t.nextYearLabel):(this._periodButtonText=t.formatYearRange(...this._formatMinAndMaxYearLabels()),this._periodButtonDescription=t.formatYearRangeLabel(...this._formatMinAndMaxYearLabels()),this._periodButtonLabel=t.switchToMonthViewLabel,this._prevButtonLabel=t.prevMultiYearLabel,this._nextButtonLabel=t.nextMultiYearLabel);}_isSameView(e,t){return this.calendar.currentView=="month"?this._dateAdapter.getYear(e)==this._dateAdapter.getYear(t)&&this._dateAdapter.getMonth(e)==this._dateAdapter.getMonth(t):this.calendar.currentView=="year"?this._dateAdapter.getYear(e)==this._dateAdapter.getYear(t):kl(this._dateAdapter,e,t,this.calendar.minDate,this.calendar.maxDate)}_formatMinAndMaxYearLabels(){let t=this._dateAdapter.getYear(this.calendar.activeDate)-zi(this._dateAdapter,this.calendar.activeDate,this.calendar.minDate,this.calendar.maxDate),i=t+at-1,a=this._dateAdapter.getYearName(this._dateAdapter.createDate(t,0,1)),o=this._dateAdapter.getYearName(this._dateAdapter.createDate(i,0,1));return [a,o]}_periodButtonLabelId=T(Y).getId("mat-calendar-period-label-");static \u0275fac=function(t){return new(t||n)};static \u0275cmp=TE({type:n,selectors:[["mat-calendar-header"]],exportAs:["matCalendarHeader"],ngContentSelectors:Ih,decls:17,vars:13,consts:[[1,"mat-calendar-header"],[1,"mat-calendar-controls"],["aria-live","polite",1,"cdk-visually-hidden",3,"id"],["matButton","","type","button",1,"mat-calendar-period-button",3,"click"],["aria-hidden","true"],["viewBox","0 0 10 5","focusable","false","aria-hidden","true",1,"mat-calendar-arrow"],["points","0,0 5,5 10,0"],[1,"mat-calendar-spacer"],["matIconButton","","type","button","disabledInteractive","",1,"mat-calendar-previous-button",3,"click","disabled","matTooltip"],["viewBox","0 0 24 24","focusable","false","aria-hidden","true"],["d","M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"],["matIconButton","","type","button","disabledInteractive","",1,"mat-calendar-next-button",3,"click","disabled","matTooltip"],["d","M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"]],template:function(t,i){t&1&&(gD(),yi(0,"div",0)(1,"div",1)(2,"span",2),UD(3),jc$1(),yi(4,"button",3),nh$1("click",function(){return i.currentPeriodClicked()}),yi(5,"span",4),UD(6),jc$1(),Gu(),yi(7,"svg",5),Yp(8,"polygon",6),jc$1()(),zu(),Yp(9,"div",7),mD(10),yi(11,"button",8),nh$1("click",function(){return i.previousClicked()}),Gu(),yi(12,"svg",9),Yp(13,"path",10),jc$1()(),zu(),yi(14,"button",11),nh$1("click",function(){return i.nextClicked()}),Gu(),yi(15,"svg",9),Yp(16,"path",12),jc$1()()()()),t&2&&(Wv(2),Zp("id",i._periodButtonLabelId),Wv(),Ih$1(i.periodButtonDescription),Wv(),Qp("aria-label",i.periodButtonLabel)("aria-describedby",i._periodButtonLabelId),Wv(2),Ih$1(i.periodButtonText),Wv(),fh$1("mat-calendar-invert",i.calendar.currentView!=="month"),Wv(4),Zp("disabled",!i.previousEnabled())("matTooltip",i.prevButtonLabel),Qp("aria-label",i.prevButtonLabel),Wv(3),Zp("disabled",!i.nextEnabled())("matTooltip",i.nextButtonLabel),Qp("aria-label",i.nextButtonLabel));},dependencies:[Lr$1,Ss$1,Fi],encapsulation:2})}return n})(),yr=(()=>{class n{_dateAdapter=T(Xe$1,{optional:true});_dateFormats=T(Ur,{optional:true});_changeDetectorRef=T(L0);_elementRef=T(Dr$1);headerComponent;_calendarHeaderPortal;_intlChanges;_moveFocusOnNextTick=false;get startAt(){return this._startAt}set startAt(e){this._startAt=this._dateAdapter.getValidDateOrNull(this._dateAdapter.deserialize(e));}_startAt=null;startView="month";get selected(){return this._selected}set selected(e){e instanceof ct?this._selected=e:this._selected=this._dateAdapter.getValidDateOrNull(this._dateAdapter.deserialize(e));}_selected=null;get minDate(){return this._minDate}set minDate(e){this._minDate=this._dateAdapter.getValidDateOrNull(this._dateAdapter.deserialize(e));}_minDate=null;get maxDate(){return this._maxDate}set maxDate(e){this._maxDate=this._dateAdapter.getValidDateOrNull(this._dateAdapter.deserialize(e));}_maxDate=null;dateFilter;dateClass;comparisonStart=null;comparisonEnd=null;startDateAccessibleName=null;endDateAccessibleName=null;selectedChange=new $e;yearSelected=new $e;monthSelected=new $e;viewChanged=new $e(true);_userSelection=new $e;_userDragDrop=new $e;monthView;yearView;multiYearView;get activeDate(){return this._clampedActiveDate}set activeDate(e){this._clampedActiveDate=this._dateAdapter.clampDate(e,this.minDate,this.maxDate),this.stateChanges.next(),this._changeDetectorRef.markForCheck();}_clampedActiveDate;get currentView(){return this._currentView}set currentView(e){let t=this._currentView!==e?e:null;this._currentView=e,this._moveFocusOnNextTick=true,this._changeDetectorRef.markForCheck(),t&&(this.stateChanges.next(),this.viewChanged.emit(t));}_currentView;_activeDrag=null;stateChanges=new ne;constructor(){this._intlChanges=T(bi).changes.subscribe(()=>{this._changeDetectorRef.markForCheck(),this.stateChanges.next();});}ngAfterContentInit(){this._calendarHeaderPortal=new bt(this.headerComponent||Dl),this.activeDate=this.startAt||this._dateAdapter.today(),this._currentView=this.startView;}ngAfterViewChecked(){this._moveFocusOnNextTick&&(this._moveFocusOnNextTick=false,this.focusActiveCell());}ngOnDestroy(){this._intlChanges.unsubscribe(),this.stateChanges.complete();}ngOnChanges(e){let t=e.minDate&&!this._dateAdapter.sameDate(e.minDate.previousValue,e.minDate.currentValue)?e.minDate:void 0,i=e.maxDate&&!this._dateAdapter.sameDate(e.maxDate.previousValue,e.maxDate.currentValue)?e.maxDate:void 0,a=t||i||e.dateFilter;if(a&&!a.firstChange){let o=this._getCurrentViewComponent();o&&(this._elementRef.nativeElement.contains(jt())&&(this._moveFocusOnNextTick=true),this._changeDetectorRef.detectChanges(),o._init());}this.stateChanges.next();}focusActiveCell(){this._getCurrentViewComponent()?._focusActiveCell(false);}updateTodaysDate(){this._getCurrentViewComponent()?._init();}_dateSelected(e){let t=e.value;(this.selected instanceof ct||t&&!this._dateAdapter.sameDate(t,this.selected))&&this.selectedChange.emit(t),this._userSelection.emit(e);}_yearSelectedInMultiYearView(e){this.yearSelected.emit(e);}_monthSelectedInYearView(e){this.monthSelected.emit(e);}_goToDateInView(e,t){this.activeDate=e,this.currentView=t;}_dragStarted(e){this._activeDrag=e;}_dragEnded(e){this._activeDrag&&(e.value&&this._userDragDrop.emit(e),this._activeDrag=null);}_getCurrentViewComponent(){return this.monthView||this.yearView||this.multiYearView}static \u0275fac=function(t){return new(t||n)};static \u0275cmp=TE({type:n,selectors:[["mat-calendar"]],viewQuery:function(t,i){if(t&1&&sh$1(bl,5)(yl,5)(vl,5),t&2){let a;vD(a=ID())&&(i.monthView=a.first),vD(a=ID())&&(i.yearView=a.first),vD(a=ID())&&(i.multiYearView=a.first);}},hostAttrs:[1,"mat-calendar"],inputs:{headerComponent:"headerComponent",startAt:"startAt",startView:"startView",selected:"selected",minDate:"minDate",maxDate:"maxDate",dateFilter:"dateFilter",dateClass:"dateClass",comparisonStart:"comparisonStart",comparisonEnd:"comparisonEnd",startDateAccessibleName:"startDateAccessibleName",endDateAccessibleName:"endDateAccessibleName"},outputs:{selectedChange:"selectedChange",yearSelected:"yearSelected",monthSelected:"monthSelected",viewChanged:"viewChanged",_userSelection:"_userSelection",_userDragDrop:"_userDragDrop"},exportAs:["matCalendar"],features:[JD([Hh]),zm$1],decls:5,vars:2,consts:[[3,"cdkPortalOutlet"],["cdkMonitorSubtreeFocus","","tabindex","-1",1,"mat-calendar-content"],[3,"activeDate","selected","dateFilter","maxDate","minDate","dateClass","comparisonStart","comparisonEnd","startDateAccessibleName","endDateAccessibleName","activeDrag"],[3,"activeDate","selected","dateFilter","maxDate","minDate","dateClass"],[3,"activeDateChange","_userSelection","dragStarted","dragEnded","activeDate","selected","dateFilter","maxDate","minDate","dateClass","comparisonStart","comparisonEnd","startDateAccessibleName","endDateAccessibleName","activeDrag"],[3,"activeDateChange","monthSelected","selectedChange","activeDate","selected","dateFilter","maxDate","minDate","dateClass"],[3,"activeDateChange","yearSelected","selectedChange","activeDate","selected","dateFilter","maxDate","minDate","dateClass"]],template:function(t,i){if(t&1&&($p(0,Eh,0,0,"ng-template",0),yi(1,"div",1),QE(2,Th,1,11,"mat-month-view",2)(3,Ah,1,6,"mat-year-view",3)(4,Rh,1,6,"mat-multi-year-view",3),jc$1()),t&2){let a;Zp("cdkPortalOutlet",i._calendarHeaderPortal),Wv(2),ZE((a=i.currentView)==="month"?2:a==="year"?3:a==="multi-year"?4:-1);}},dependencies:[st,ba$1,bl,yl,vl],styles:[`.mat-calendar {
  display: block;
  line-height: normal;
  font-family: var(--mat-datepicker-calendar-text-font, var(--mat-sys-body-medium-font));
  font-size: var(--mat-datepicker-calendar-text-size, var(--mat-sys-body-medium-size));
}

.mat-calendar-header {
  padding: 8px 8px 0 8px;
}

.mat-calendar-content {
  padding: 0 8px 8px 8px;
  outline: none;
}

.mat-calendar-controls {
  display: flex;
  align-items: center;
  margin: 5% calc(4.7142857143% - 16px);
}

.mat-calendar-spacer {
  flex: 1 1 auto;
}

.mat-calendar-period-button {
  min-width: 0;
  margin: 0 8px;
  font-size: var(--mat-datepicker-calendar-period-button-text-size, var(--mat-sys-title-small-size));
  font-weight: var(--mat-datepicker-calendar-period-button-text-weight, var(--mat-sys-title-small-weight));
  --mat-button-text-label-text-color: var(--mat-datepicker-calendar-period-button-text-color, var(--mat-sys-on-surface-variant));
}

.mat-calendar-arrow {
  display: inline-block;
  width: 10px;
  height: 5px;
  margin: 0 0 0 5px;
  vertical-align: middle;
  fill: var(--mat-datepicker-calendar-period-button-icon-color, var(--mat-sys-on-surface-variant));
}
.mat-calendar-arrow.mat-calendar-invert {
  transform: rotate(180deg);
}
[dir=rtl] .mat-calendar-arrow {
  margin: 0 5px 0 0;
}
@media (forced-colors: active) {
  .mat-calendar-arrow {
    fill: CanvasText;
  }
}

.mat-datepicker-content .mat-calendar-previous-button:not(.mat-mdc-button-disabled),
.mat-datepicker-content .mat-calendar-next-button:not(.mat-mdc-button-disabled) {
  color: var(--mat-datepicker-calendar-navigation-button-icon-color, var(--mat-sys-on-surface-variant));
}
[dir=rtl] .mat-calendar-previous-button,
[dir=rtl] .mat-calendar-next-button {
  transform: rotate(180deg);
}

.mat-calendar-table {
  border-spacing: 0;
  border-collapse: collapse;
  width: 100%;
}

.mat-calendar-table-header th {
  text-align: center;
  padding: 0 0 8px 0;
  color: var(--mat-datepicker-calendar-header-text-color, var(--mat-sys-on-surface-variant));
  font-size: var(--mat-datepicker-calendar-header-text-size, var(--mat-sys-title-small-size));
  font-weight: var(--mat-datepicker-calendar-header-text-weight, var(--mat-sys-title-small-weight));
}

.mat-calendar-table-header-divider {
  position: relative;
  height: 1px;
}
.mat-calendar-table-header-divider::after {
  content: "";
  position: absolute;
  top: 0;
  left: -8px;
  right: -8px;
  height: 1px;
  background: var(--mat-datepicker-calendar-header-divider-color, transparent);
}

.mat-calendar-body-cell-content::before {
  margin: calc(calc(var(--mat-focus-indicator-border-width, 3px) + 3px) * -1);
}

.mat-calendar-body-cell:focus-visible .mat-focus-indicator::before {
  content: "";
}
`],encapsulation:2})}return n})();var Uh=(()=>{class n{_elementRef=T(Dr$1);_animationsDisabled=B();_changeDetectorRef=T(L0);_globalModel=T(jn);_dateAdapter=T(Xe$1);_ngZone=T(Se);_rangeSelectionStrategy=T(wl,{optional:true});_stateChanges;_model;_eventCleanups;_animationFallback;_calendar;color;datepicker;comparisonStart=null;comparisonEnd=null;startDateAccessibleName=null;endDateAccessibleName=null;_isAbove=false;_animationDone=new ne;_isAnimating=false;_closeButtonText;_closeButtonFocused=false;_actionsPortal=null;_dialogLabelId=null;constructor(){if(T(W$1).load(ce),this._closeButtonText=T(bi).closeCalendarLabel,!this._animationsDisabled){let e=this._elementRef.nativeElement,t=T(MI);this._eventCleanups=this._ngZone.runOutsideAngular(()=>[t.listen(e,"animationstart",this._handleAnimationEvent),t.listen(e,"animationend",this._handleAnimationEvent),t.listen(e,"animationcancel",this._handleAnimationEvent)]);}}ngAfterViewInit(){this._stateChanges=this.datepicker.stateChanges.subscribe(()=>{this._changeDetectorRef.markForCheck();}),this._calendar.focusActiveCell();}ngOnDestroy(){clearTimeout(this._animationFallback),this._eventCleanups?.forEach(e=>e()),this._stateChanges?.unsubscribe(),this._animationDone.complete();}_handleUserSelection(e){let t=this._model.selection,i=e.value,a=t instanceof ct;if(a&&this._rangeSelectionStrategy){let o=this._rangeSelectionStrategy.selectionFinished(i,t,e.event);this._model.updateSelection(o,this);}else i&&(a||!this._dateAdapter.sameDate(i,t))&&this._model.add(i);(!this._model||this._model.isComplete())&&!this._actionsPortal&&this.datepicker.close();}_handleUserDragDrop(e){this._model.updateSelection(e.value,this);}_startExitAnimation(){this._elementRef.nativeElement.classList.add("mat-datepicker-content-exit"),this._animationsDisabled?this._animationDone.next():(clearTimeout(this._animationFallback),this._animationFallback=setTimeout(()=>{this._isAnimating||this._animationDone.next();},200));}_handleAnimationEvent=e=>{let t=this._elementRef.nativeElement;e.target!==t||!e.animationName.startsWith("_mat-datepicker-content")||(clearTimeout(this._animationFallback),this._isAnimating=e.type==="animationstart",t.classList.toggle("mat-datepicker-content-animating",this._isAnimating),this._isAnimating||this._animationDone.next());};_getSelected(){return this._model.selection}_applyPendingSelection(){this._model!==this._globalModel&&this._globalModel.updateSelection(this._model.selection,this);}_assignActions(e,t){this._model=e?this._globalModel.clone():this._globalModel,this._actionsPortal=e,t&&this._changeDetectorRef.detectChanges();}static \u0275fac=function(t){return new(t||n)};static \u0275cmp=TE({type:n,selectors:[["mat-datepicker-content"]],viewQuery:function(t,i){if(t&1&&sh$1(yr,5),t&2){let a;vD(a=ID())&&(i._calendar=a.first);}},hostAttrs:[1,"mat-datepicker-content"],hostVars:6,hostBindings:function(t,i){t&2&&(RD(i.color?"mat-"+i.color:""),fh$1("mat-datepicker-content-touch",i.datepicker.touchUi)("mat-datepicker-content-animations-enabled",!i._animationsDisabled));},inputs:{color:"color"},exportAs:["matDatepickerContent"],decls:5,vars:26,consts:[["cdkTrapFocus","","role","dialog",1,"mat-datepicker-content-container"],[3,"yearSelected","monthSelected","viewChanged","_userSelection","_userDragDrop","id","startAt","startView","minDate","maxDate","dateFilter","headerComponent","selected","dateClass","comparisonStart","comparisonEnd","startDateAccessibleName","endDateAccessibleName"],[3,"cdkPortalOutlet"],["type","button","matButton","elevated",1,"mat-datepicker-close-button",3,"focus","blur","click","color"]],template:function(t,i){t&1&&(yi(0,"div",0)(1,"mat-calendar",1),nh$1("yearSelected",function(o){return i.datepicker._selectYear(o)})("monthSelected",function(o){return i.datepicker._selectMonth(o)})("viewChanged",function(o){return i.datepicker._viewChanged(o)})("_userSelection",function(o){return i._handleUserSelection(o)})("_userDragDrop",function(o){return i._handleUserDragDrop(o)}),jc$1(),$p(2,Fh,0,0,"ng-template",2),yi(3,"button",3),nh$1("focus",function(){return i._closeButtonFocused=true})("blur",function(){return i._closeButtonFocused=false})("click",function(){return i.datepicker.close()}),UD(4),jc$1()()),t&2&&(fh$1("mat-datepicker-content-container-with-custom-header",i.datepicker.calendarHeaderComponent)("mat-datepicker-content-container-with-actions",i._actionsPortal),Qp("aria-modal",true)("aria-labelledby",i._dialogLabelId??void 0),Wv(),RD(i.datepicker.panelClass),Zp("id",i.datepicker.id)("startAt",i.datepicker.startAt)("startView",i.datepicker.startView)("minDate",i.datepicker._getMinDate())("maxDate",i.datepicker._getMaxDate())("dateFilter",i.datepicker._getDateFilter())("headerComponent",i.datepicker.calendarHeaderComponent)("selected",i._getSelected())("dateClass",i.datepicker.dateClass)("comparisonStart",i.comparisonStart)("comparisonEnd",i.comparisonEnd)("startDateAccessibleName",i.startDateAccessibleName)("endDateAccessibleName",i.endDateAccessibleName),Wv(),Zp("cdkPortalOutlet",i._actionsPortal),Wv(),fh$1("cdk-visually-hidden",!i._closeButtonFocused),Zp("color",i.color||"primary"),Wv(),Ih$1(i._closeButtonText));},dependencies:[Ta$1,yr,st,Lr$1],styles:[`@keyframes _mat-datepicker-content-dropdown-enter {
  from {
    opacity: 0;
    transform: scaleY(0.8);
  }
  to {
    opacity: 1;
    transform: none;
  }
}
@keyframes _mat-datepicker-content-dialog-enter {
  from {
    opacity: 0;
    transform: scale(0.8);
  }
  to {
    opacity: 1;
    transform: none;
  }
}
@keyframes _mat-datepicker-content-exit {
  from {
    opacity: 1;
  }
  to {
    opacity: 0;
  }
}
.mat-datepicker-content {
  display: block;
  background-color: var(--mat-datepicker-calendar-container-background-color, var(--mat-sys-surface-container-high));
  color: var(--mat-datepicker-calendar-container-text-color, var(--mat-sys-on-surface));
  box-shadow: var(--mat-datepicker-calendar-container-elevation-shadow, 0px 0px 0px 0px rgba(0, 0, 0, 0.2), 0px 0px 0px 0px rgba(0, 0, 0, 0.14), 0px 0px 0px 0px rgba(0, 0, 0, 0.12));
  border-radius: var(--mat-datepicker-calendar-container-shape, var(--mat-sys-corner-large));
}
.mat-datepicker-content.mat-datepicker-content-animations-enabled {
  animation: _mat-datepicker-content-dropdown-enter 120ms cubic-bezier(0, 0, 0.2, 1);
}
.mat-datepicker-content .mat-calendar {
  width: 296px;
  height: 354px;
}
.mat-datepicker-content .mat-datepicker-content-container-with-custom-header .mat-calendar {
  height: auto;
}
.mat-datepicker-content .mat-datepicker-close-button {
  position: absolute;
  top: 100%;
  left: 0;
  margin-top: 8px;
}
.mat-datepicker-content-animating .mat-datepicker-content .mat-datepicker-close-button {
  display: none;
}

.mat-datepicker-content-container {
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}

.mat-datepicker-content-touch {
  display: block;
  max-height: 80vh;
  box-shadow: var(--mat-datepicker-calendar-container-touch-elevation-shadow, 0px 0px 0px 0px rgba(0, 0, 0, 0.2), 0px 0px 0px 0px rgba(0, 0, 0, 0.14), 0px 0px 0px 0px rgba(0, 0, 0, 0.12));
  border-radius: var(--mat-datepicker-calendar-container-touch-shape, var(--mat-sys-corner-extra-large));
  position: relative;
  overflow: visible;
  min-height: fit-content;
}
.mat-datepicker-content-touch.mat-datepicker-content-animations-enabled {
  animation: _mat-datepicker-content-dialog-enter 150ms cubic-bezier(0, 0, 0.2, 1);
}
.mat-datepicker-content-touch .mat-datepicker-content-container {
  min-height: 312px;
  max-height: 788px;
  min-width: 250px;
  max-width: 750px;
}
.mat-datepicker-content-touch .mat-calendar {
  width: 100%;
  height: auto;
}

.mat-datepicker-content-exit.mat-datepicker-content-animations-enabled {
  animation: _mat-datepicker-content-exit 100ms linear;
}

@media all and (orientation: landscape) {
  .mat-datepicker-content-touch .mat-datepicker-content-container {
    width: 64vh;
    height: 80vh;
  }
}
@media all and (orientation: portrait) {
  .mat-datepicker-content-touch .mat-datepicker-content-container {
    width: 80vw;
    height: 100vw;
  }
  .mat-datepicker-content-touch .mat-datepicker-content-container-with-actions {
    height: 115vw;
  }
}
`],encapsulation:2})}return n})();var Wh=(()=>{class n{static \u0275fac=function(t){return new(t||n)};static \u0275dir=SE({type:n,selectors:[["","matDatepickerToggleIcon",""]]})}return n})(),qh=(()=>{class n{_intl=T(bi);_changeDetectorRef=T(L0);_stateChanges=j.EMPTY;datepicker;tabIndex=null;ariaLabel;get disabled(){return this._disabled===void 0&&this.datepicker?this.datepicker.disabled:!!this._disabled}set disabled(e){this._disabled=e;}_disabled;disableRipple=false;_customIcon;_button;constructor(){let e=T(new Ah$1("tabindex"),{optional:true}),t=Number(e);this.tabIndex=t||t===0?t:null;}ngOnChanges(e){e.datepicker&&this._watchStateChanges();}ngOnDestroy(){this._stateChanges.unsubscribe();}ngAfterContentInit(){this._watchStateChanges();}_open(e){this.datepicker&&!this.disabled&&(this.datepicker.open(),e.stopPropagation());}_watchStateChanges(){let e=this.datepicker?this.datepicker.stateChanges:rg(),t=this.datepicker&&this.datepicker.datepickerInput?this.datepicker.datepickerInput.stateChanges:rg(),i=this.datepicker?kg(this.datepicker.openedStream,this.datepicker.closedStream):rg();this._stateChanges.unsubscribe(),this._stateChanges=kg(this._intl.changes,e,t,i).subscribe(()=>this._changeDetectorRef.markForCheck());}static \u0275fac=function(t){return new(t||n)};static \u0275cmp=TE({type:n,selectors:[["mat-datepicker-toggle"]],contentQueries:function(t,i,a){if(t&1&&ih$1(a,Wh,5),t&2){let o;vD(o=ID())&&(i._customIcon=o.first);}},viewQuery:function(t,i){if(t&1&&sh$1(Oh,5),t&2){let a;vD(a=ID())&&(i._button=a.first);}},hostAttrs:[1,"mat-datepicker-toggle"],hostVars:8,hostBindings:function(t,i){t&1&&nh$1("click",function(o){return i._open(o)}),t&2&&(Qp("tabindex",null)("data-mat-calendar",i.datepicker?i.datepicker.id:null),fh$1("mat-datepicker-toggle-active",i.datepicker&&i.datepicker.opened)("mat-accent",i.datepicker&&i.datepicker.color==="accent")("mat-warn",i.datepicker&&i.datepicker.color==="warn"));},inputs:{datepicker:[0,"for","datepicker"],tabIndex:"tabIndex",ariaLabel:[0,"aria-label","ariaLabel"],disabled:[2,"disabled","disabled",j0],disableRipple:"disableRipple"},exportAs:["matDatepickerToggle"],features:[zm$1],ngContentSelectors:Vh,decls:4,vars:7,consts:[["button",""],["matIconButton","","type","button",3,"tabIndex","disabled","disableRipple"],["viewBox","0 0 24 24","width","24px","height","24px","fill","currentColor","focusable","false","aria-hidden","true",1,"mat-datepicker-toggle-default-icon"],["d","M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7z"]],template:function(t,i){t&1&&(gD(Ph),yi(0,"button",1,0),QE(2,Lh,2,0,":svg:svg",2),mD(3),jc$1()),t&2&&(Zp("tabIndex",i.disabled?-1:i.tabIndex)("disabled",i.disabled)("disableRipple",i.disableRipple),Qp("aria-haspopup",i.datepicker?"dialog":null)("aria-label",i.ariaLabel||i._intl.openCalendarLabel)("aria-expanded",i.datepicker?i.datepicker.opened:null),Wv(2),ZE(i._customIcon?-1:2));},dependencies:[Ss$1],styles:[`.mat-datepicker-toggle {
  pointer-events: auto;
  color: var(--mat-datepicker-toggle-icon-color, var(--mat-sys-on-surface-variant));
}
.mat-datepicker-toggle button {
  color: inherit;
}

.mat-datepicker-toggle-active {
  color: var(--mat-datepicker-toggle-active-state-icon-color, var(--mat-sys-primary));
}

@media (forced-colors: active) {
  .mat-datepicker-toggle-default-icon {
    color: CanvasText;
  }
}
`],encapsulation:2})}return n})();var Dr=(()=>{class n{static \u0275fac=function(t){return new(t||n)};static \u0275mod=bE({type:n});static \u0275inj=cu$1({providers:[bi],imports:[Br$1,Kt$1,ti,et,Uh,qh,Dl,jo$1,di]})}return n})();var Mr=(()=>{class n{static \u0275fac=function(t){return new(t||n)};static \u0275mod=bE({type:n});static \u0275inj=cu$1({imports:[zs$1,jo$1,zs$1]})}return n})();var Sr=(()=>{class n{static \u0275fac=function(t){return new(t||n)};static \u0275mod=bE({type:n});static \u0275inj=cu$1({imports:[jo$2,Ye,Mi$1,jo$1,Oi]})}return n})();var Ir=(()=>{class n{static \u0275fac=function(t){return new(t||n)};static \u0275mod=bE({type:n});static \u0275inj=cu$1({imports:[di,jo$1,di]})}return n})();var Qh=["switch"],Yh=["*"];function Kh(n,r){n&1&&(yi(0,"span",11),Gu(),yi(1,"svg",13),Yp(2,"path",14),jc$1(),yi(3,"svg",15),Yp(4,"path",16),jc$1()());}var $h=new x("mat-slide-toggle-default-options",{providedIn:"root",factory:()=>({disableToggleValue:false,hideIcon:false,disabledInteractive:false})}),Gn=class{source;checked;constructor(r,e){this.source=r,this.checked=e;}},Xh=(()=>{class n{_elementRef=T(Dr$1);_focusMonitor=T(le);_changeDetectorRef=T(L0);defaults=T($h);_onChange=e=>{};_onTouched=()=>{};_validatorOnChange=()=>{};_uniqueId;_checked=false;_createChangeEvent(e){return new Gn(this,e)}_labelId;get buttonId(){return `${this.id||this._uniqueId}-button`}_switchElement;focus(){this._switchElement.nativeElement.focus();}_noopAnimations=B();_focused=false;name=null;id;labelPosition="after";ariaLabel=null;ariaLabelledby=null;ariaDescribedby;required=false;color;disabled=false;disableRipple=false;tabIndex=0;get checked(){return this._checked}set checked(e){this._checked=e,this._changeDetectorRef.markForCheck();}hideIcon;disabledInteractive;change=new $e;toggleChange=new $e;get inputId(){return `${this.id||this._uniqueId}-input`}constructor(){T(W$1).load(bn$1);let e=T(new Ah$1("tabindex"),{optional:true}),t=this.defaults;this.tabIndex=e==null?0:parseInt(e)||0,this.color=t.color||"accent",this.id=this._uniqueId=T(Y).getId("mat-mdc-slide-toggle-"),this.hideIcon=t.hideIcon??false,this.disabledInteractive=t.disabledInteractive??false,this._labelId=this._uniqueId+"-label";}ngAfterContentInit(){this._focusMonitor.monitor(this._elementRef,true).subscribe(e=>{e==="keyboard"||e==="program"?(this._focused=true,this._changeDetectorRef.markForCheck()):e||Promise.resolve().then(()=>{this._focused=false,this._onTouched(),this._changeDetectorRef.markForCheck();});});}ngOnChanges(e){e.required&&this._validatorOnChange();}ngOnDestroy(){this._focusMonitor.stopMonitoring(this._elementRef);}writeValue(e){this.checked=!!e;}registerOnChange(e){this._onChange=e;}registerOnTouched(e){this._onTouched=e;}validate(e){return this.required&&e.value!==true?{required:true}:null}registerOnValidatorChange(e){this._validatorOnChange=e;}setDisabledState(e){this.disabled=e,this._changeDetectorRef.markForCheck();}toggle(){this.checked=!this.checked,this._onChange(this.checked);}_emitChangeEvent(){this._onChange(this.checked),this.change.emit(this._createChangeEvent(this.checked));}_handleClick(){this.disabled||(this.toggleChange.emit(),this.defaults.disableToggleValue||(this.checked=!this.checked,this._onChange(this.checked),this.change.emit(new Gn(this,this.checked))));}_getAriaLabelledBy(){return this.ariaLabelledby?this.ariaLabelledby:this.ariaLabel?null:this._labelId}static \u0275fac=function(t){return new(t||n)};static \u0275cmp=TE({type:n,selectors:[["mat-slide-toggle"]],viewQuery:function(t,i){if(t&1&&sh$1(Qh,5),t&2){let a;vD(a=ID())&&(i._switchElement=a.first);}},hostAttrs:[1,"mat-mdc-slide-toggle"],hostVars:13,hostBindings:function(t,i){t&2&&(eh$1("id",i.id),Qp("tabindex",null)("aria-label",null)("name",null)("aria-labelledby",null),RD(i.color?"mat-"+i.color:""),fh$1("mat-mdc-slide-toggle-focused",i._focused)("mat-mdc-slide-toggle-checked",i.checked)("_mat-animation-noopable",i._noopAnimations));},inputs:{name:"name",id:"id",labelPosition:"labelPosition",ariaLabel:[0,"aria-label","ariaLabel"],ariaLabelledby:[0,"aria-labelledby","ariaLabelledby"],ariaDescribedby:[0,"aria-describedby","ariaDescribedby"],required:[2,"required","required",j0],color:"color",disabled:[2,"disabled","disabled",j0],disableRipple:[2,"disableRipple","disableRipple",j0],tabIndex:[2,"tabIndex","tabIndex",e=>e==null?0:V0(e)],checked:[2,"checked","checked",j0],hideIcon:[2,"hideIcon","hideIcon",j0],disabledInteractive:[2,"disabledInteractive","disabledInteractive",j0]},outputs:{change:"change",toggleChange:"toggleChange"},exportAs:["matSlideToggle"],features:[JD([{provide:Xe,useExisting:vo(()=>n),multi:true},{provide:Ze,useExisting:n,multi:true}]),zm$1],ngContentSelectors:Yh,decls:14,vars:27,consts:[["switch",""],["mat-internal-form-field","",3,"labelPosition"],["role","switch","type","button",1,"mdc-switch",3,"click","tabIndex","disabled"],[1,"mat-mdc-slide-toggle-touch-target"],[1,"mdc-switch__track"],[1,"mdc-switch__handle-track"],[1,"mdc-switch__handle"],[1,"mdc-switch__shadow"],[1,"mdc-elevation-overlay"],[1,"mdc-switch__ripple"],["mat-ripple","",1,"mat-mdc-slide-toggle-ripple","mat-focus-indicator",3,"matRippleTrigger","matRippleDisabled","matRippleCentered"],[1,"mdc-switch__icons"],[1,"mdc-label",3,"click","for"],["viewBox","0 0 24 24","aria-hidden","true",1,"mdc-switch__icon","mdc-switch__icon--on"],["d","M19.69,5.23L8.96,15.96l-4.23-4.23L2.96,13.5l6,6L21.46,7L19.69,5.23z"],["viewBox","0 0 24 24","aria-hidden","true",1,"mdc-switch__icon","mdc-switch__icon--off"],["d","M20 13H4v-2h16v2z"]],template:function(t,i){if(t&1&&(gD(),yi(0,"div",1)(1,"button",2,0),nh$1("click",function(){return i._handleClick()}),Yp(3,"div",3)(4,"span",4),yi(5,"span",5)(6,"span",6)(7,"span",7),Yp(8,"span",8),jc$1(),yi(9,"span",9),Yp(10,"span",10),jc$1(),QE(11,Kh,5,0,"span",11),jc$1()()(),yi(12,"label",12),nh$1("click",function(o){return o.stopPropagation()}),mD(13),jc$1()()),t&2){let a=DD(2);Zp("labelPosition",i.labelPosition),Wv(),fh$1("mdc-switch--selected",i.checked)("mdc-switch--unselected",!i.checked)("mdc-switch--checked",i.checked)("mdc-switch--disabled",i.disabled)("mat-mdc-slide-toggle-disabled-interactive",i.disabledInteractive),Zp("tabIndex",i.disabled&&!i.disabledInteractive?-1:i.tabIndex)("disabled",i.disabled&&!i.disabledInteractive),Qp("id",i.buttonId)("name",i.name)("aria-label",i.ariaLabel)("aria-labelledby",i._getAriaLabelledBy())("aria-describedby",i.ariaDescribedby)("aria-required",i.required||null)("aria-checked",i.checked)("aria-disabled",i.disabled&&i.disabledInteractive?"true":null),Wv(9),Zp("matRippleTrigger",a)("matRippleDisabled",i.disableRipple||i.disabled)("matRippleCentered",true),Wv(),ZE(i.hideIcon?-1:11),Wv(),Zp("for",i.buttonId),Qp("id",i._labelId);}},dependencies:[rr$1,bf],styles:[`.mdc-switch {
  align-items: center;
  background: none;
  border: none;
  cursor: pointer;
  display: inline-flex;
  flex-shrink: 0;
  margin: 0;
  outline: none;
  overflow: visible;
  padding: 0;
  position: relative;
  width: var(--mat-slide-toggle-track-width, 52px);
}
.mdc-switch.mdc-switch--disabled {
  cursor: default;
  pointer-events: none;
}
.mdc-switch.mat-mdc-slide-toggle-disabled-interactive {
  pointer-events: auto;
}

.mdc-switch__track {
  overflow: hidden;
  position: relative;
  width: 100%;
  height: var(--mat-slide-toggle-track-height, 32px);
  border-radius: var(--mat-slide-toggle-track-shape, var(--mat-sys-corner-full));
}
.mdc-switch--disabled.mdc-switch .mdc-switch__track {
  opacity: var(--mat-slide-toggle-disabled-track-opacity, 0.12);
}
.mdc-switch__track::before, .mdc-switch__track::after {
  border: 1px solid transparent;
  border-radius: inherit;
  box-sizing: border-box;
  content: "";
  height: 100%;
  left: 0;
  position: absolute;
  width: 100%;
  border-width: var(--mat-slide-toggle-track-outline-width, 2px);
  border-color: var(--mat-slide-toggle-track-outline-color, var(--mat-sys-outline));
}
.mdc-switch--selected .mdc-switch__track::before, .mdc-switch--selected .mdc-switch__track::after {
  border-width: var(--mat-slide-toggle-selected-track-outline-width, 2px);
  border-color: var(--mat-slide-toggle-selected-track-outline-color, transparent);
}
.mdc-switch--disabled .mdc-switch__track::before, .mdc-switch--disabled .mdc-switch__track::after {
  border-width: var(--mat-slide-toggle-disabled-unselected-track-outline-width, 2px);
  border-color: var(--mat-slide-toggle-disabled-unselected-track-outline-color, var(--mat-sys-on-surface));
}
@media (forced-colors: active) {
  .mdc-switch__track {
    border-color: currentColor;
  }
}
.mdc-switch__track::before {
  transition: transform 75ms 0ms cubic-bezier(0, 0, 0.2, 1);
  transform: translateX(0);
  background: var(--mat-slide-toggle-unselected-track-color, var(--mat-sys-surface-variant));
}
.mdc-switch--selected .mdc-switch__track::before {
  transition: transform 75ms 0ms cubic-bezier(0.4, 0, 0.6, 1);
  transform: translateX(100%);
}
[dir=rtl] .mdc-switch--selected .mdc-switch--selected .mdc-switch__track::before {
  transform: translateX(-100%);
}
.mdc-switch--selected .mdc-switch__track::before {
  opacity: var(--mat-slide-toggle-hidden-track-opacity, 0);
  transition: var(--mat-slide-toggle-hidden-track-transition, opacity 75ms);
}
.mdc-switch--unselected .mdc-switch__track::before {
  opacity: var(--mat-slide-toggle-visible-track-opacity, 1);
  transition: var(--mat-slide-toggle-visible-track-transition, opacity 75ms);
}
.mdc-switch:enabled:hover:not(:focus):not(:active) .mdc-switch__track::before {
  background: var(--mat-slide-toggle-unselected-hover-track-color, var(--mat-sys-surface-variant));
}
.mdc-switch:enabled:focus:not(:active) .mdc-switch__track::before {
  background: var(--mat-slide-toggle-unselected-focus-track-color, var(--mat-sys-surface-variant));
}
.mdc-switch:enabled:active .mdc-switch__track::before {
  background: var(--mat-slide-toggle-unselected-pressed-track-color, var(--mat-sys-surface-variant));
}
.mat-mdc-slide-toggle-disabled-interactive.mdc-switch--disabled:hover:not(:focus):not(:active) .mdc-switch__track::before, .mat-mdc-slide-toggle-disabled-interactive.mdc-switch--disabled:focus:not(:active) .mdc-switch__track::before, .mat-mdc-slide-toggle-disabled-interactive.mdc-switch--disabled:active .mdc-switch__track::before, .mdc-switch.mdc-switch--disabled .mdc-switch__track::before {
  background: var(--mat-slide-toggle-disabled-unselected-track-color, var(--mat-sys-surface-variant));
}
.mdc-switch__track::after {
  transform: translateX(-100%);
  background: var(--mat-slide-toggle-selected-track-color, var(--mat-sys-primary));
}
[dir=rtl] .mdc-switch__track::after {
  transform: translateX(100%);
}
.mdc-switch--selected .mdc-switch__track::after {
  transform: translateX(0);
}
.mdc-switch--selected .mdc-switch__track::after {
  opacity: var(--mat-slide-toggle-visible-track-opacity, 1);
  transition: var(--mat-slide-toggle-visible-track-transition, opacity 75ms);
}
.mdc-switch--unselected .mdc-switch__track::after {
  opacity: var(--mat-slide-toggle-hidden-track-opacity, 0);
  transition: var(--mat-slide-toggle-hidden-track-transition, opacity 75ms);
}
.mdc-switch:enabled:hover:not(:focus):not(:active) .mdc-switch__track::after {
  background: var(--mat-slide-toggle-selected-hover-track-color, var(--mat-sys-primary));
}
.mdc-switch:enabled:focus:not(:active) .mdc-switch__track::after {
  background: var(--mat-slide-toggle-selected-focus-track-color, var(--mat-sys-primary));
}
.mdc-switch:enabled:active .mdc-switch__track::after {
  background: var(--mat-slide-toggle-selected-pressed-track-color, var(--mat-sys-primary));
}
.mat-mdc-slide-toggle-disabled-interactive.mdc-switch--disabled:hover:not(:focus):not(:active) .mdc-switch__track::after, .mat-mdc-slide-toggle-disabled-interactive.mdc-switch--disabled:focus:not(:active) .mdc-switch__track::after, .mat-mdc-slide-toggle-disabled-interactive.mdc-switch--disabled:active .mdc-switch__track::after, .mdc-switch.mdc-switch--disabled .mdc-switch__track::after {
  background: var(--mat-slide-toggle-disabled-selected-track-color, var(--mat-sys-on-surface));
}

.mdc-switch__handle-track {
  height: 100%;
  pointer-events: none;
  position: absolute;
  top: 0;
  transition: transform 75ms 0ms cubic-bezier(0.4, 0, 0.2, 1);
  left: 0;
  right: auto;
  transform: translateX(0);
  width: calc(100% - var(--mat-slide-toggle-handle-width));
}
[dir=rtl] .mdc-switch__handle-track {
  left: auto;
  right: 0;
}
.mdc-switch--selected .mdc-switch__handle-track {
  transform: translateX(100%);
}
[dir=rtl] .mdc-switch--selected .mdc-switch__handle-track {
  transform: translateX(-100%);
}

.mdc-switch__handle {
  display: flex;
  pointer-events: auto;
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  left: 0;
  right: auto;
  transition: width 75ms cubic-bezier(0.4, 0, 0.2, 1), height 75ms cubic-bezier(0.4, 0, 0.2, 1), margin 75ms cubic-bezier(0.4, 0, 0.2, 1);
  width: var(--mat-slide-toggle-handle-width);
  height: var(--mat-slide-toggle-handle-height);
  border-radius: var(--mat-slide-toggle-handle-shape, var(--mat-sys-corner-full));
}
[dir=rtl] .mdc-switch__handle {
  left: auto;
  right: 0;
}
.mat-mdc-slide-toggle .mdc-switch--unselected .mdc-switch__handle {
  width: var(--mat-slide-toggle-unselected-handle-size, 16px);
  height: var(--mat-slide-toggle-unselected-handle-size, 16px);
  margin: var(--mat-slide-toggle-unselected-handle-horizontal-margin, 0 8px);
}
.mat-mdc-slide-toggle .mdc-switch--unselected .mdc-switch__handle:has(.mdc-switch__icons) {
  margin: var(--mat-slide-toggle-unselected-with-icon-handle-horizontal-margin, 0 4px);
}
.mat-mdc-slide-toggle .mdc-switch--selected .mdc-switch__handle {
  width: var(--mat-slide-toggle-selected-handle-size, 24px);
  height: var(--mat-slide-toggle-selected-handle-size, 24px);
  margin: var(--mat-slide-toggle-selected-handle-horizontal-margin, 0 24px);
}
.mat-mdc-slide-toggle .mdc-switch--selected .mdc-switch__handle:has(.mdc-switch__icons) {
  margin: var(--mat-slide-toggle-selected-with-icon-handle-horizontal-margin, 0 24px);
}
.mat-mdc-slide-toggle .mdc-switch__handle:has(.mdc-switch__icons) {
  width: var(--mat-slide-toggle-with-icon-handle-size, 24px);
  height: var(--mat-slide-toggle-with-icon-handle-size, 24px);
}
.mat-mdc-slide-toggle .mdc-switch:active:not(.mdc-switch--disabled) .mdc-switch__handle {
  width: var(--mat-slide-toggle-pressed-handle-size, 28px);
  height: var(--mat-slide-toggle-pressed-handle-size, 28px);
}
.mat-mdc-slide-toggle .mdc-switch--selected:active:not(.mdc-switch--disabled) .mdc-switch__handle {
  margin: var(--mat-slide-toggle-selected-pressed-handle-horizontal-margin, 0 22px);
}
.mat-mdc-slide-toggle .mdc-switch--unselected:active:not(.mdc-switch--disabled) .mdc-switch__handle {
  margin: var(--mat-slide-toggle-unselected-pressed-handle-horizontal-margin, 0 2px);
}
.mdc-switch--disabled.mdc-switch--selected .mdc-switch__handle::after {
  opacity: var(--mat-slide-toggle-disabled-selected-handle-opacity, 1);
}
.mdc-switch--disabled.mdc-switch--unselected .mdc-switch__handle::after {
  opacity: var(--mat-slide-toggle-disabled-unselected-handle-opacity, 0.38);
}
.mdc-switch__handle::before, .mdc-switch__handle::after {
  border: 1px solid transparent;
  border-radius: inherit;
  box-sizing: border-box;
  content: "";
  width: 100%;
  height: 100%;
  left: 0;
  position: absolute;
  top: 0;
  transition: background-color 75ms 0ms cubic-bezier(0.4, 0, 0.2, 1), border-color 75ms 0ms cubic-bezier(0.4, 0, 0.2, 1);
  z-index: -1;
}
@media (forced-colors: active) {
  .mdc-switch__handle::before, .mdc-switch__handle::after {
    border-color: currentColor;
  }
}
.mdc-switch--selected:enabled .mdc-switch__handle::after {
  background: var(--mat-slide-toggle-selected-handle-color, var(--mat-sys-on-primary));
}
.mdc-switch--selected:enabled:hover:not(:focus):not(:active) .mdc-switch__handle::after {
  background: var(--mat-slide-toggle-selected-hover-handle-color, var(--mat-sys-primary-container));
}
.mdc-switch--selected:enabled:focus:not(:active) .mdc-switch__handle::after {
  background: var(--mat-slide-toggle-selected-focus-handle-color, var(--mat-sys-primary-container));
}
.mdc-switch--selected:enabled:active .mdc-switch__handle::after {
  background: var(--mat-slide-toggle-selected-pressed-handle-color, var(--mat-sys-primary-container));
}
.mat-mdc-slide-toggle-disabled-interactive.mdc-switch--disabled.mdc-switch--selected:hover:not(:focus):not(:active) .mdc-switch__handle::after, .mat-mdc-slide-toggle-disabled-interactive.mdc-switch--disabled.mdc-switch--selected:focus:not(:active) .mdc-switch__handle::after, .mat-mdc-slide-toggle-disabled-interactive.mdc-switch--disabled.mdc-switch--selected:active .mdc-switch__handle::after, .mdc-switch--selected.mdc-switch--disabled .mdc-switch__handle::after {
  background: var(--mat-slide-toggle-disabled-selected-handle-color, var(--mat-sys-surface));
}
.mdc-switch--unselected:enabled .mdc-switch__handle::after {
  background: var(--mat-slide-toggle-unselected-handle-color, var(--mat-sys-outline));
}
.mdc-switch--unselected:enabled:hover:not(:focus):not(:active) .mdc-switch__handle::after {
  background: var(--mat-slide-toggle-unselected-hover-handle-color, var(--mat-sys-on-surface-variant));
}
.mdc-switch--unselected:enabled:focus:not(:active) .mdc-switch__handle::after {
  background: var(--mat-slide-toggle-unselected-focus-handle-color, var(--mat-sys-on-surface-variant));
}
.mdc-switch--unselected:enabled:active .mdc-switch__handle::after {
  background: var(--mat-slide-toggle-unselected-pressed-handle-color, var(--mat-sys-on-surface-variant));
}
.mdc-switch--unselected.mdc-switch--disabled .mdc-switch__handle::after {
  background: var(--mat-slide-toggle-disabled-unselected-handle-color, var(--mat-sys-on-surface));
}
.mdc-switch__handle::before {
  background: var(--mat-slide-toggle-handle-surface-color);
}

.mdc-switch__shadow {
  border-radius: inherit;
  bottom: 0;
  left: 0;
  position: absolute;
  right: 0;
  top: 0;
}
.mdc-switch:enabled .mdc-switch__shadow {
  box-shadow: var(--mat-slide-toggle-handle-elevation-shadow);
}
.mat-mdc-slide-toggle-disabled-interactive.mdc-switch--disabled:hover:not(:focus):not(:active) .mdc-switch__shadow, .mat-mdc-slide-toggle-disabled-interactive.mdc-switch--disabled:focus:not(:active) .mdc-switch__shadow, .mat-mdc-slide-toggle-disabled-interactive.mdc-switch--disabled:active .mdc-switch__shadow, .mdc-switch.mdc-switch--disabled .mdc-switch__shadow {
  box-shadow: var(--mat-slide-toggle-disabled-handle-elevation-shadow);
}

.mdc-switch__ripple {
  left: 50%;
  position: absolute;
  top: 50%;
  transform: translate(-50%, -50%);
  z-index: -1;
  width: var(--mat-slide-toggle-state-layer-size, 40px);
  height: var(--mat-slide-toggle-state-layer-size, 40px);
}
.mdc-switch__ripple::after {
  content: "";
  opacity: 0;
}
.mdc-switch--disabled .mdc-switch__ripple::after {
  display: none;
}
.mat-mdc-slide-toggle-disabled-interactive .mdc-switch__ripple::after {
  display: block;
}
.mdc-switch:hover .mdc-switch__ripple::after {
  transition: 75ms opacity cubic-bezier(0, 0, 0.2, 1);
}
.mat-mdc-slide-toggle-disabled-interactive.mdc-switch--disabled:enabled:focus .mdc-switch__ripple::after, .mat-mdc-slide-toggle-disabled-interactive.mdc-switch--disabled:enabled:active .mdc-switch__ripple::after, .mat-mdc-slide-toggle-disabled-interactive.mdc-switch--disabled:enabled:hover:not(:focus) .mdc-switch__ripple::after, .mdc-switch--unselected:enabled:hover:not(:focus) .mdc-switch__ripple::after {
  background: var(--mat-slide-toggle-unselected-hover-state-layer-color, var(--mat-sys-on-surface));
  opacity: var(--mat-slide-toggle-unselected-hover-state-layer-opacity, var(--mat-sys-hover-state-layer-opacity));
}
.mdc-switch--unselected:enabled:focus .mdc-switch__ripple::after {
  background: var(--mat-slide-toggle-unselected-focus-state-layer-color, var(--mat-sys-on-surface));
  opacity: var(--mat-slide-toggle-unselected-focus-state-layer-opacity, var(--mat-sys-focus-state-layer-opacity));
}
.mdc-switch--unselected:enabled:active .mdc-switch__ripple::after {
  background: var(--mat-slide-toggle-unselected-pressed-state-layer-color, var(--mat-sys-on-surface));
  opacity: var(--mat-slide-toggle-unselected-pressed-state-layer-opacity, var(--mat-sys-pressed-state-layer-opacity));
  transition: opacity 75ms linear;
}
.mdc-switch--selected:enabled:hover:not(:focus) .mdc-switch__ripple::after {
  background: var(--mat-slide-toggle-selected-hover-state-layer-color, var(--mat-sys-primary));
  opacity: var(--mat-slide-toggle-selected-hover-state-layer-opacity, var(--mat-sys-hover-state-layer-opacity));
}
.mdc-switch--selected:enabled:focus .mdc-switch__ripple::after {
  background: var(--mat-slide-toggle-selected-focus-state-layer-color, var(--mat-sys-primary));
  opacity: var(--mat-slide-toggle-selected-focus-state-layer-opacity, var(--mat-sys-focus-state-layer-opacity));
}
.mdc-switch--selected:enabled:active .mdc-switch__ripple::after {
  background: var(--mat-slide-toggle-selected-pressed-state-layer-color, var(--mat-sys-primary));
  opacity: var(--mat-slide-toggle-selected-pressed-state-layer-opacity, var(--mat-sys-pressed-state-layer-opacity));
  transition: opacity 75ms linear;
}

.mdc-switch__icons {
  position: relative;
  height: 100%;
  width: 100%;
  z-index: 1;
  transform: translateZ(0);
}
.mdc-switch--disabled.mdc-switch--unselected .mdc-switch__icons {
  opacity: var(--mat-slide-toggle-disabled-unselected-icon-opacity, 0.38);
}
.mdc-switch--disabled.mdc-switch--selected .mdc-switch__icons {
  opacity: var(--mat-slide-toggle-disabled-selected-icon-opacity, 0.38);
}

.mdc-switch__icon {
  bottom: 0;
  left: 0;
  margin: auto;
  position: absolute;
  right: 0;
  top: 0;
  opacity: 0;
  transition: opacity 30ms 0ms cubic-bezier(0.4, 0, 1, 1);
}
.mdc-switch--unselected .mdc-switch__icon {
  width: var(--mat-slide-toggle-unselected-icon-size, 16px);
  height: var(--mat-slide-toggle-unselected-icon-size, 16px);
  fill: var(--mat-slide-toggle-unselected-icon-color, var(--mat-sys-surface-variant));
}
.mdc-switch--unselected.mdc-switch--disabled .mdc-switch__icon {
  fill: var(--mat-slide-toggle-disabled-unselected-icon-color, var(--mat-sys-surface-variant));
}
.mdc-switch--selected .mdc-switch__icon {
  width: var(--mat-slide-toggle-selected-icon-size, 16px);
  height: var(--mat-slide-toggle-selected-icon-size, 16px);
  fill: var(--mat-slide-toggle-selected-icon-color, var(--mat-sys-on-primary-container));
}
.mdc-switch--selected.mdc-switch--disabled .mdc-switch__icon {
  fill: var(--mat-slide-toggle-disabled-selected-icon-color, var(--mat-sys-on-surface));
}

.mdc-switch--selected .mdc-switch__icon--on,
.mdc-switch--unselected .mdc-switch__icon--off {
  opacity: 1;
  transition: opacity 45ms 30ms cubic-bezier(0, 0, 0.2, 1);
}

.mat-mdc-slide-toggle {
  -webkit-user-select: none;
  user-select: none;
  display: inline-block;
  -webkit-tap-highlight-color: transparent;
  outline: 0;
}
.mat-mdc-slide-toggle .mat-mdc-slide-toggle-ripple,
.mat-mdc-slide-toggle .mdc-switch__ripple::after {
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  position: absolute;
  border-radius: 50%;
  pointer-events: none;
}
.mat-mdc-slide-toggle .mat-mdc-slide-toggle-ripple:not(:empty),
.mat-mdc-slide-toggle .mdc-switch__ripple::after:not(:empty) {
  transform: translateZ(0);
}
.mat-mdc-slide-toggle.mat-mdc-slide-toggle-focused .mat-focus-indicator::before {
  content: "";
}
.mat-mdc-slide-toggle .mat-internal-form-field {
  color: var(--mat-slide-toggle-label-text-color, var(--mat-sys-on-surface));
  font-family: var(--mat-slide-toggle-label-text-font, var(--mat-sys-body-medium-font));
  line-height: var(--mat-slide-toggle-label-text-line-height, var(--mat-sys-body-medium-line-height));
  font-size: var(--mat-slide-toggle-label-text-size, var(--mat-sys-body-medium-size));
  letter-spacing: var(--mat-slide-toggle-label-text-tracking, var(--mat-sys-body-medium-tracking));
  font-weight: var(--mat-slide-toggle-label-text-weight, var(--mat-sys-body-medium-weight));
}
.mat-mdc-slide-toggle .mat-ripple-element {
  opacity: 0.12;
}
.mat-mdc-slide-toggle .mat-focus-indicator::before {
  border-radius: 50%;
}
.mat-mdc-slide-toggle._mat-animation-noopable .mdc-switch__handle-track,
.mat-mdc-slide-toggle._mat-animation-noopable .mdc-switch__icon,
.mat-mdc-slide-toggle._mat-animation-noopable .mdc-switch__handle::before,
.mat-mdc-slide-toggle._mat-animation-noopable .mdc-switch__handle::after,
.mat-mdc-slide-toggle._mat-animation-noopable .mdc-switch__track::before,
.mat-mdc-slide-toggle._mat-animation-noopable .mdc-switch__track::after {
  transition: none;
}
.mat-mdc-slide-toggle .mdc-switch:enabled + .mdc-label {
  cursor: pointer;
}
.mat-mdc-slide-toggle .mdc-switch--disabled + label {
  color: var(--mat-slide-toggle-disabled-label-text-color, var(--mat-sys-on-surface));
}
.mat-mdc-slide-toggle label:empty {
  display: none;
}

.mat-mdc-slide-toggle-touch-target {
  position: absolute;
  top: 50%;
  left: 50%;
  height: var(--mat-slide-toggle-touch-target-size, 48px);
  width: 100%;
  transform: translate(-50%, -50%);
  display: var(--mat-slide-toggle-touch-target-display, block);
}
[dir=rtl] .mat-mdc-slide-toggle-touch-target {
  left: auto;
  right: 50%;
  transform: translate(50%, -50%);
}
`],encapsulation:2})}return n})(),Er=(()=>{class n{static \u0275fac=function(t){return new(t||n)};static \u0275mod=bE({type:n});static \u0275inj=cu$1({imports:[Xh,jo$1]})}return n})();var Tr=(()=>{class n{static \u0275fac=function(t){return new(t||n)};static \u0275mod=bE({type:n});static \u0275inj=cu$1({imports:[Ye,jo$1]})}return n})();var Ar=(()=>{class n{static \u0275fac=function(t){return new(t||n)};static \u0275mod=bE({type:n});static \u0275inj=cu$1({imports:[jo$1]})}return n})();var Zh=["*"];function Jh(n,r){n&1&&mD(0);}var Rr=(()=>{class n{_elementRef=T(Dr$1);focus(){this._elementRef.nativeElement.focus();}static \u0275fac=function(t){return new(t||n)};static \u0275dir=SE({type:n,selectors:[["","cdkStepHeader",""]],hostAttrs:["role","tab"]})}return n})(),Fr=(()=>{class n{template=T(hr$1);static \u0275fac=function(t){return new(t||n)};static \u0275dir=SE({type:n,selectors:[["","cdkStepLabel",""]]})}return n})();var Kt={NUMBER:"number",EDIT:"edit",DONE:"done",ERROR:"error"},ep=new x("STEPPER_GLOBAL_OPTIONS"),Un=(()=>{class n{_stepperOptions;_stepper=T(Wn);_displayDefaultIndicatorType;stepLabel;_childForms;content;stepControl;get interacted(){return this._interacted()}set interacted(e){this._interacted.set(e);}_interacted=Vo$1(false);interactedStream=new $e;label;errorMessage;ariaLabel;ariaLabelledby;get state(){return this._state()}set state(e){this._state.set(e);}_state=Vo$1(void 0);get editable(){return this._editable()}set editable(e){this._editable.set(e);}_editable=Vo$1(true);optional=false;get completed(){let e=this._completedOverride(),t=this._interacted();return e??(t&&(!this.stepControl||this.stepControl.valid))}set completed(e){this._completedOverride.set(e);}_completedOverride=Vo$1(null);index=Vo$1(-1);isSelected=gw(()=>this._stepper.selectedIndex===this.index());indicatorType=gw(()=>{let e=this.isSelected(),t=this.completed,i=this._state()??Kt.NUMBER,a=this._editable();return this._showError()&&this.hasError&&!e?Kt.ERROR:this._displayDefaultIndicatorType?!t||e?Kt.NUMBER:a?Kt.EDIT:Kt.DONE:t&&!e?Kt.DONE:t&&e?i:a&&e?Kt.EDIT:i});isNavigable=gw(()=>{let e=this.isSelected();return this.completed||e||!this._stepper.linear});get hasError(){let e=this._customError();return e??this._getDefaultError()}set hasError(e){this._customError.set(e);}_customError=Vo$1(null);_getDefaultError(){return this.interacted&&!!this.stepControl?.invalid}constructor(){let e=T(ep,{optional:true});this._stepperOptions=e||{},this._displayDefaultIndicatorType=this._stepperOptions.displayDefaultIndicatorType!==false;}select(){this._stepper.selected=this;}reset(){this._interacted.set(false),this._completedOverride()!=null&&this._completedOverride.set(false),this._customError()!=null&&this._customError.set(false),this.stepControl&&(this._childForms?.forEach(e=>e.resetForm?.()),this.stepControl.reset());}ngOnChanges(){this._stepper._stateChanged();}_markAsInteracted(){this._interacted()||(this._interacted.set(true),this.interactedStream.emit(this));}_showError(){return this._stepperOptions.showError??this._customError()!=null}static \u0275fac=function(t){return new(t||n)};static \u0275cmp=TE({type:n,selectors:[["cdk-step"]],contentQueries:function(t,i,a){if(t&1&&ih$1(a,Fr,5)(a,Ge,5),t&2){let o;vD(o=ID())&&(i.stepLabel=o.first),vD(o=ID())&&(i._childForms=o);}},viewQuery:function(t,i){if(t&1&&sh$1(hr$1,7),t&2){let a;vD(a=ID())&&(i.content=a.first);}},inputs:{stepControl:"stepControl",label:"label",errorMessage:"errorMessage",ariaLabel:[0,"aria-label","ariaLabel"],ariaLabelledby:[0,"aria-labelledby","ariaLabelledby"],state:"state",editable:[2,"editable","editable",j0],optional:[2,"optional","optional",j0],completed:[2,"completed","completed",j0],hasError:[2,"hasError","hasError",j0]},outputs:{interactedStream:"interacted"},exportAs:["cdkStep"],features:[zm$1],ngContentSelectors:Zh,decls:1,vars:0,template:function(t,i){t&1&&(gD(),Up(0,Jh,1,0,"ng-template"));},encapsulation:2})}return n})(),Wn=(()=>{class n{_dir=T(Er$2,{optional:true});_changeDetectorRef=T(L0);_elementRef=T(Dr$1);_destroyed=new ne;_keyManager;_steps;steps=new ri;_stepHeader;_sortedHeaders=new ri;get linear(){return this._linear()}set linear(e){this._linear.set(e);}_linear=Vo$1(false);get selectedIndex(){return this._selectedIndex()}set selectedIndex(e){this._steps?(this._isValidIndex(e),this.selectedIndex!==e&&(this.selected?._markAsInteracted(),!this._anyControlsInvalidOrPending(e)&&(e>=this.selectedIndex||this.steps.toArray()[e].editable)&&this._updateSelectedItemIndex(e))):this._selectedIndex.set(e);}_selectedIndex=Vo$1(0);get selected(){return this.steps?this.steps.toArray()[this.selectedIndex]:void 0}set selected(e){this.selectedIndex=e&&this.steps?this.steps.toArray().indexOf(e):-1;}selectionChange=new $e;selectedIndexChange=new $e;_groupId=T(Y).getId("cdk-stepper-");get orientation(){return this._orientation}set orientation(e){this._orientation=e,this._keyManager&&this._keyManager.withVerticalOrientation(e==="vertical");}_orientation="horizontal";ngAfterContentInit(){this._steps.changes.pipe(Hl$1(this._steps),Bl$1(this._destroyed)).subscribe(e=>{this.steps.reset(e.filter(t=>t._stepper===this)),this.steps.forEach((t,i)=>t.index.set(i)),this.steps.notifyOnChanges();});}ngAfterViewInit(){if(this._stepHeader.changes.pipe(Hl$1(this._stepHeader),Bl$1(this._destroyed)).subscribe(e=>{this._sortedHeaders.reset(e.toArray().sort((t,i)=>t._elementRef.nativeElement.compareDocumentPosition(i._elementRef.nativeElement)&Node.DOCUMENT_POSITION_FOLLOWING?-1:1)),this._sortedHeaders.notifyOnChanges();}),this._keyManager=new ni(this._sortedHeaders).withWrap().withHomeAndEnd().withVerticalOrientation(this._orientation==="vertical"),this._keyManager.updateActiveItem(this.selectedIndex),(this._dir?this._dir.change:rg()).pipe(Hl$1(this._layoutDirection()),Bl$1(this._destroyed)).subscribe(e=>this._keyManager?.withHorizontalOrientation(e)),this._keyManager.updateActiveItem(this.selectedIndex),this.steps.changes.subscribe(()=>{this.selected||this._selectedIndex.set(Math.max(this.selectedIndex-1,0));}),this._isValidIndex(this.selectedIndex)||this._selectedIndex.set(0),this.linear&&this.selectedIndex>0){let e=this.steps.toArray().slice(0,this._selectedIndex());for(let t of e)t._markAsInteracted();}}ngOnDestroy(){this._keyManager?.destroy(),this.steps.destroy(),this._sortedHeaders.destroy(),this._destroyed.next(),this._destroyed.complete();}next(){this.selectedIndex=Math.min(this._selectedIndex()+1,this.steps.length-1);}previous(){this.selectedIndex=Math.max(this._selectedIndex()-1,0);}reset(){this._updateSelectedItemIndex(0),this.steps.forEach(e=>e.reset()),this._stateChanged();}_getStepLabelId(e){return `${this._groupId}-label-${e}`}_getStepContentId(e){return `${this._groupId}-content-${e}`}_stateChanged(){this._changeDetectorRef.markForCheck();}_getAnimationDirection(e){let t=e-this._selectedIndex();return t<0?this._layoutDirection()==="rtl"?"next":"previous":t>0?this._layoutDirection()==="rtl"?"previous":"next":"current"}_getFocusIndex(){return this._keyManager?this._keyManager.activeItemIndex:this._selectedIndex()}_updateSelectedItemIndex(e){let t=this.steps.toArray(),i=this._selectedIndex();this.selectionChange.emit({selectedIndex:e,previouslySelectedIndex:i,selectedStep:t[e],previouslySelectedStep:t[i]}),this._keyManager&&(this._containsFocus()?this._keyManager.setActiveItem(e):this._keyManager.updateActiveItem(e)),this._selectedIndex.set(e),this.selectedIndexChange.emit(e),this._stateChanged();}_onKeydown(e){let t=$(e),i=e.keyCode,a=this._keyManager;a?.activeItemIndex!=null&&!t&&(i===32||i===13)?(this.selectedIndex=a.activeItemIndex,e.preventDefault()):a?.setFocusOrigin("keyboard").onKeydown(e);}_anyControlsInvalidOrPending(e){return this.linear&&e>=0?this.steps.toArray().slice(0,e).some(t=>{let i=t.stepControl;return (i?i.invalid||i.pending||!t.interacted:!t.completed)&&!t.optional&&!t._completedOverride()}):false}_layoutDirection(){return this._dir&&this._dir.value==="rtl"?"rtl":"ltr"}_containsFocus(){let e=this._elementRef.nativeElement,t=jt();return e===t||e.contains(t)}_isValidIndex(e){return e>-1&&(!this.steps||e<this.steps.length)}static \u0275fac=function(t){return new(t||n)};static \u0275dir=SE({type:n,selectors:[["","cdkStepper",""]],contentQueries:function(t,i,a){if(t&1&&ih$1(a,Un,5)(a,Rr,5),t&2){let o;vD(o=ID())&&(i._steps=o),vD(o=ID())&&(i._stepHeader=o);}},inputs:{linear:[2,"linear","linear",j0],selectedIndex:[2,"selectedIndex","selectedIndex",V0],selected:"selected",orientation:"orientation"},outputs:{selectionChange:"selectionChange",selectedIndexChange:"selectedIndexChange"},exportAs:["cdkStepper"]})}return n})();var Ml=(()=>{class n{static \u0275fac=function(t){return new(t||n)};static \u0275mod=bE({type:n});static \u0275inj=cu$1({imports:[jo$1]})}return n})();var tp=(n,r,e)=>({index:n,active:r,optional:e});function ip(n,r){if(n&1&&Xp(0,2),n&2){let e=pD();Zp("ngTemplateOutlet",e.iconOverrides[e.state])("ngTemplateOutletContext",nw(2,tp,e.index,e.active,e.optional));}}function np(n,r){if(n&1&&(yi(0,"span",7),UD(1),jc$1()),n&2){let e=pD(2);Wv(),Ih$1(e._getDefaultTextForState(e.state));}}function ap(n,r){if(n&1&&(yi(0,"span",8),UD(1),jc$1()),n&2){let e=pD(3);Wv(),Ih$1(e._intl.completedLabel);}}function rp(n,r){if(n&1&&(yi(0,"span",8),UD(1),jc$1()),n&2){let e=pD(3);Wv(),Ih$1(e._intl.editableLabel);}}function op(n,r){if(n&1&&(QE(0,ap,2,1,"span",8)(1,rp,2,1,"span",8),yi(2,"mat-icon",7),UD(3),jc$1()),n&2){let e=pD(2);ZE(e.state==="done"?0:e.state==="edit"?1:-1),Wv(3),Ih$1(e._getDefaultTextForState(e.state));}}function sp(n,r){if(n&1&&QE(0,np,2,1,"span",7)(1,op,4,2),n&2){let t=pD();ZE((t.state)==="number"?0:1);}}function lp(n,r){n&1&&(yi(0,"div",4),Xp(1,9),jc$1()),n&2&&(Wv(),Zp("ngTemplateOutlet",r.template));}function dp(n,r){if(n&1&&(yi(0,"div",4),UD(1),jc$1()),n&2){let e=pD();Wv(),Ih$1(e.label);}}function cp(n,r){if(n&1&&(yi(0,"div",5),UD(1),jc$1()),n&2){let e=pD();Wv(),Ih$1(e._intl.optionalLabel);}}function mp(n,r){if(n&1&&(yi(0,"div",6),UD(1),jc$1()),n&2){let e=pD();Wv(),Ih$1(e.errorMessage);}}var Sl=["*"];function hp(n,r){}function pp(n,r){if(n&1&&(mD(0),$p(1,hp,0,0,"ng-template",0)),n&2){let e=pD();Wv(),Zp("cdkPortalOutlet",e._portal);}}var up=["animatedContainer"],Il=n=>({steps:n}),El=n=>({step:n});function _p(n,r){n&1&&mD(0);}function fp(n,r){if(n&1&&(yi(0,"div",5),Xp(1,9)(2,6),jc$1()),n&2){let e=pD(2),t=DD(6);Wv(),Zp("ngTemplateOutlet",e.headerPrefix()),Wv(),Zp("ngTemplateOutlet",t)("ngTemplateOutletContext",ew(3,Il,e.steps));}}function gp(n,r){if(n&1&&Xp(0,6),n&2){let e=pD(2),t=DD(6);Zp("ngTemplateOutlet",t)("ngTemplateOutletContext",ew(2,Il,e.steps));}}function bp(n,r){if(n&1&&(yi(0,"div",10,2),Xp(2,9),jc$1()),n&2){let e=r.$implicit,t=r.$index,i=pD(2);RD("mat-horizontal-stepper-content-"+i._getAnimationDirection(t)),Zp("id",i._getStepContentId(t)),Qp("aria-labelledby",i._getStepLabelId(t))("inert",i.selectedIndex===t?null:""),Wv(2),Zp("ngTemplateOutlet",e.content);}}function vp(n,r){if(n&1&&(yi(0,"div",3),QE(1,fp,3,5,"div",5)(2,gp,1,4,"ng-container",6),yi(3,"div",7),JE(4,bp,3,6,"div",8,KE),jc$1()()),n&2){let e=pD();Wv(),ZE(e.headerPrefix()?1:2),Wv(3),XE(e.steps);}}function yp(n,r){if(n&1&&Xp(0,9),n&2){let e=pD(2);Zp("ngTemplateOutlet",e.headerPrefix());}}function xp(n,r){if(n&1&&(yi(0,"div",11),Xp(1,6),yi(2,"div",12,2)(4,"div",13)(5,"div",14),Xp(6,9),jc$1()()()()),n&2){let e=r.$implicit,t=r.$index,i=r.$index,a=r.$count,o=pD(2),c=DD(4);Wv(),Zp("ngTemplateOutlet",c)("ngTemplateOutletContext",ew(11,El,e)),Wv(),fh$1("mat-stepper-vertical-line",i!==a-1)("mat-vertical-content-container-active",o.selectedIndex===t),Qp("inert",o.selectedIndex===t?null:"")("aria-label",o.ariaLabel),Wv(2),Zp("id",o._getStepContentId(t)),Qp("aria-labelledby",o._getStepLabelId(t)),Wv(2),Zp("ngTemplateOutlet",e.content);}}function wp(n,r){if(n&1&&(yi(0,"div",4),QE(1,yp,1,1,"ng-container",9),JE(2,xp,7,13,"div",11,KE),jc$1()),n&2){let e=pD();Wv(),ZE(e.headerPrefix()?1:-1),Wv(),XE(e.steps);}}function kp(n,r){if(n&1){let e=iD();yi(0,"mat-step-header",15),nh$1("click",function(){let i=ku(e).step;return Ou(i.select())})("keydown",function(i){ku(e);let a=pD();return Ou(a._onKeydown(i))}),jc$1();}if(n&2){let e=r.step,t=pD();fh$1("mat-horizontal-stepper-header",t.orientation==="horizontal")("mat-vertical-stepper-header",t.orientation==="vertical"),Zp("tabIndex",t._getFocusIndex()===e.index()?0:-1)("id",t._getStepLabelId(e.index()))("index",e.index())("state",e.indicatorType())("label",e.stepLabel||e.label)("selected",e.isSelected())("active",e.isNavigable())("optional",e.optional)("errorMessage",e.errorMessage)("iconOverrides",t._iconOverrides)("disableRipple",t.disableRipple||!e.isNavigable())("color",e.color||t.color),Qp("role",t.orientation==="horizontal"?"tab":"button")("aria-posinset",t.orientation==="horizontal"?e.index()+1:null)("aria-setsize",t.orientation==="horizontal"?t.steps.length:null)("aria-selected",t.orientation==="horizontal"?e.isSelected():null)("aria-current",t.orientation==="vertical"&&e.isSelected()?"step":null)("aria-disabled",t.orientation==="vertical"&&e.isSelected()?"true":null)("aria-expanded",t.orientation==="vertical"?e.isSelected():null)("aria-controls",t._getStepContentId(e.index()))("aria-label",e.ariaLabel||null)("aria-labelledby",!e.ariaLabel&&e.ariaLabelledby?e.ariaLabelledby:null)("aria-disabled",e.isNavigable()?null:true);}}function Cp(n,r){n&1&&Yp(0,"div",17);}function Dp(n,r){if(n&1&&(Xp(0,6),QE(1,Cp,1,0,"div",17)),n&2){let e=r.$implicit,t=r.$index,i=r.$count;pD(2);let a=DD(4);Zp("ngTemplateOutlet",a)("ngTemplateOutletContext",ew(3,El,e)),Wv(),ZE(t!==i-1?1:-1);}}function Mp(n,r){if(n&1&&(yi(0,"div",16),JE(1,Dp,2,5,null,null,KE),jc$1()),n&2){let e=r.steps,t=pD();Qp("aria-label",t.ariaLabel),Wv(),XE(e);}}var Or=(()=>{class n extends Fr{static \u0275fac=(()=>{let e;return function(i){return (e||(e=fy(n)))(i||n)}})();static \u0275dir=SE({type:n,selectors:[["","matStepLabel",""]],features:[Hp$1]})}return n})(),Sp=(()=>{class n{changes=new ne;optionalLabel="Optional";completedLabel="Completed";editableLabel="Editable";static \u0275fac=function(t){return new(t||n)};static \u0275prov=Er$1({token:n,factory:n.\u0275fac})}return n})(),Pr=(()=>{class n extends Rr{_intl=T(Sp);_focusMonitor=T(le);_intlSubscription;state;label;errorMessage;iconOverrides;index;selected=false;active=false;optional=false;disableRipple=false;color;constructor(){super();let e=T(W$1);e.load(bn$1),e.load(ce);let t=T(L0);this._intlSubscription=this._intl.changes.subscribe(()=>t.markForCheck());}ngAfterViewInit(){this._focusMonitor.monitor(this._elementRef,true);}ngOnDestroy(){this._intlSubscription.unsubscribe(),this._focusMonitor.stopMonitoring(this._elementRef);}focus(e,t){e?this._focusMonitor.focusVia(this._elementRef,e,t):this._elementRef.nativeElement.focus(t);}_stringLabel(){return this.label instanceof Or?null:this.label}_templateLabel(){return this.label instanceof Or?this.label:null}_getHostElement(){return this._elementRef.nativeElement}_getDefaultTextForState(e){return e=="number"?`${this.index+1}`:e=="edit"?"create":e=="error"?"warning":e}_hasEmptyLabel(){return !this._stringLabel()&&!this._templateLabel()&&!this._hasOptionalLabel()&&!this._hasErrorLabel()}_hasOptionalLabel(){return this.optional&&this.state!=="error"}_hasErrorLabel(){return this.state==="error"}static \u0275fac=function(t){return new(t||n)};static \u0275cmp=TE({type:n,selectors:[["mat-step-header"]],hostAttrs:["role","",1,"mat-step-header"],hostVars:4,hostBindings:function(t,i){t&2&&(RD("mat-"+(i.color||"primary")),fh$1("mat-step-header-empty-label",i._hasEmptyLabel()));},inputs:{state:"state",label:"label",errorMessage:"errorMessage",iconOverrides:"iconOverrides",index:"index",selected:"selected",active:"active",optional:"optional",disableRipple:"disableRipple",color:"color"},features:[Hp$1],decls:10,vars:17,consts:[["matRipple","",1,"mat-step-header-ripple","mat-focus-indicator",3,"matRippleTrigger","matRippleDisabled"],[1,"mat-step-icon-content"],[3,"ngTemplateOutlet","ngTemplateOutletContext"],[1,"mat-step-label"],[1,"mat-step-text-label"],[1,"mat-step-optional"],[1,"mat-step-sub-label-error"],["aria-hidden","true"],[1,"cdk-visually-hidden"],[3,"ngTemplateOutlet"]],template:function(t,i){if(t&1&&(Yp(0,"div",0),yi(1,"div")(2,"div",1),QE(3,ip,1,6,"ng-container",2)(4,sp,2,1),jc$1()(),yi(5,"div",3),QE(6,lp,2,1,"div",4)(7,dp,2,1,"div",4),QE(8,cp,2,1,"div",5),QE(9,mp,2,1,"div",6),jc$1()),t&2){let a;Zp("matRippleTrigger",i._getHostElement())("matRippleDisabled",i.disableRipple),Wv(),RD(QD("mat-step-icon-state-",i.state," mat-step-icon")),fh$1("mat-step-icon-selected",i.selected),Wv(2),ZE(i.iconOverrides&&i.iconOverrides[i.state]?3:4),Wv(2),fh$1("mat-step-label-active",i.active)("mat-step-label-selected",i.selected)("mat-step-label-error",i.state=="error"),Wv(),ZE((a=i._templateLabel())?6:i._stringLabel()?7:-1,a),Wv(2),ZE(i._hasOptionalLabel()?8:-1),Wv(),ZE(i._hasErrorLabel()?9:-1);}},dependencies:[rr$1,jn$1,Ws],styles:[`.mat-step-header {
  overflow: hidden;
  outline: none;
  cursor: pointer;
  position: relative;
  box-sizing: content-box;
  -webkit-tap-highlight-color: transparent;
}
.mat-step-header:focus-visible .mat-focus-indicator::before {
  content: "";
}
.mat-step-header:hover[aria-disabled=true] {
  cursor: default;
}
.mat-step-header:hover:not([aria-disabled]), .mat-step-header:hover[aria-disabled=false] {
  background-color: var(--mat-stepper-header-hover-state-layer-color, color-mix(in srgb, var(--mat-sys-on-surface) calc(var(--mat-sys-hover-state-layer-opacity) * 100%), transparent));
  border-radius: var(--mat-stepper-header-hover-state-layer-shape, var(--mat-sys-corner-medium));
}
.mat-step-header.cdk-keyboard-focused, .mat-step-header.cdk-program-focused {
  background-color: var(--mat-stepper-header-focus-state-layer-color, color-mix(in srgb, var(--mat-sys-on-surface) calc(var(--mat-sys-focus-state-layer-opacity) * 100%), transparent));
  border-radius: var(--mat-stepper-header-focus-state-layer-shape, var(--mat-sys-corner-medium));
}
@media (hover: none) {
  .mat-step-header:hover {
    background: none;
  }
}
@media (forced-colors: active) {
  .mat-step-header {
    outline: solid 1px;
  }
  .mat-step-header[aria-selected=true] .mat-step-label {
    text-decoration: underline;
  }
  .mat-step-header[aria-disabled=true] {
    outline-color: GrayText;
  }
  .mat-step-header[aria-disabled=true] .mat-step-label,
  .mat-step-header[aria-disabled=true] .mat-step-icon,
  .mat-step-header[aria-disabled=true] .mat-step-optional {
    color: GrayText;
  }
}

.mat-step-optional {
  font-size: 12px;
  color: var(--mat-stepper-header-optional-label-text-color, var(--mat-sys-on-surface-variant));
}

.mat-step-sub-label-error {
  font-size: 12px;
  font-weight: normal;
}

.mat-step-icon {
  border-radius: 50%;
  height: 24px;
  width: 24px;
  flex-shrink: 0;
  position: relative;
  color: var(--mat-stepper-header-icon-foreground-color, var(--mat-sys-surface));
  background-color: var(--mat-stepper-header-icon-background-color, var(--mat-sys-on-surface-variant));
}

.mat-step-icon-content {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  display: flex;
}

.mat-step-icon .mat-icon {
  font-size: 16px;
  height: 16px;
  width: 16px;
}

.mat-step-icon-state-error {
  background-color: var(--mat-stepper-header-error-state-icon-background-color, transparent);
  color: var(--mat-stepper-header-error-state-icon-foreground-color, var(--mat-sys-error));
}
.mat-step-icon-state-error .mat-icon {
  font-size: 24px;
  height: 24px;
  width: 24px;
}

.mat-step-label {
  display: inline-block;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  min-width: 50px;
  vertical-align: middle;
  font-family: var(--mat-stepper-header-label-text-font, var(--mat-sys-title-small-font));
  font-size: var(--mat-stepper-header-label-text-size, var(--mat-sys-title-small-size));
  font-weight: var(--mat-stepper-header-label-text-weight, var(--mat-sys-title-small-weight));
  color: var(--mat-stepper-header-label-text-color, var(--mat-sys-on-surface-variant));
}
.mat-step-label.mat-step-label-active {
  color: var(--mat-stepper-header-selected-state-label-text-color, var(--mat-sys-on-surface-variant));
}
.mat-step-label.mat-step-label-error {
  color: var(--mat-stepper-header-error-state-label-text-color, var(--mat-sys-error));
  font-size: var(--mat-stepper-header-error-state-label-text-size, var(--mat-sys-title-small-size));
}
.mat-step-label.mat-step-label-selected {
  font-size: var(--mat-stepper-header-selected-state-label-text-size, var(--mat-sys-title-small-size));
  font-weight: var(--mat-stepper-header-selected-state-label-text-weight, var(--mat-sys-title-small-weight));
}
.mat-step-header-empty-label .mat-step-label {
  min-width: 0;
}

.mat-step-text-label {
  text-overflow: ellipsis;
  overflow: hidden;
}

.mat-step-header .mat-step-header-ripple {
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  position: absolute;
  pointer-events: none;
}

.mat-step-icon-selected {
  background-color: var(--mat-stepper-header-selected-state-icon-background-color, var(--mat-sys-primary));
  color: var(--mat-stepper-header-selected-state-icon-foreground-color, var(--mat-sys-on-primary));
}

.mat-step-icon-state-done {
  background-color: var(--mat-stepper-header-done-state-icon-background-color, var(--mat-sys-primary));
  color: var(--mat-stepper-header-done-state-icon-foreground-color, var(--mat-sys-on-primary));
}

.mat-step-icon-state-edit {
  background-color: var(--mat-stepper-header-edit-state-icon-background-color, var(--mat-sys-primary));
  color: var(--mat-stepper-header-edit-state-icon-foreground-color, var(--mat-sys-on-primary));
}
`],encapsulation:2})}return n})(),Ip=(()=>{class n{templateRef=T(hr$1);name;static \u0275fac=function(t){return new(t||n)};static \u0275dir=SE({type:n,selectors:[["ng-template","matStepperIcon",""]],inputs:{name:[0,"matStepperIcon","name"]}})}return n})(),Ep=(()=>{class n{_template=T(hr$1);static \u0275fac=function(t){return new(t||n)};static \u0275dir=SE({type:n,selectors:[["ng-template","matStepContent",""]]})}return n})(),Tp=(()=>{class n extends Un{_errorStateMatcher=T(nf,{skipSelf:true});_viewContainerRef=T(Ai$1);_isSelected=j.EMPTY;stepLabel=void 0;color;_lazyContent;_portal;ngAfterContentInit(){this._isSelected=this._stepper.steps.changes.pipe(Cl$1(()=>this._stepper.selectionChange.pipe(Ce(e=>e.selectedStep===this),Hl$1(this._stepper.selected===this)))).subscribe(e=>{e&&this._lazyContent&&!this._portal&&(this._portal=new at$1(this._lazyContent._template,this._viewContainerRef));});}ngOnDestroy(){this._isSelected.unsubscribe();}isErrorState(e,t){let i=this._errorStateMatcher.isErrorState(e,t),a=!!(e&&e.invalid&&this.interacted);return i||a}static \u0275fac=(()=>{let e;return function(i){return (e||(e=fy(n)))(i||n)}})();static \u0275cmp=TE({type:n,selectors:[["mat-step"]],contentQueries:function(t,i,a){if(t&1&&ih$1(a,Or,5)(a,Ep,5),t&2){let o;vD(o=ID())&&(i.stepLabel=o.first),vD(o=ID())&&(i._lazyContent=o.first);}},hostAttrs:["hidden",""],inputs:{color:"color"},exportAs:["matStep"],features:[JD([{provide:nf,useExisting:n},{provide:Un,useExisting:n}]),Hp$1],ngContentSelectors:Sl,decls:1,vars:0,consts:[[3,"cdkPortalOutlet"]],template:function(t,i){t&1&&(gD(),$p(0,pp,2,1,"ng-template"));},dependencies:[st],encapsulation:2})}return n})(),Ap=(()=>{class n extends Wn{_ngZone=T(Se);_renderer=T(MI);_animationsDisabled=B();_cleanupTransition;_isAnimating=Vo$1(false);_stepHeader=void 0;_animatedContainers;_steps=void 0;steps=new ri;_icons;animationDone=new $e;disableRipple=false;color;labelPosition="end";headerPosition="top";ariaLabel=null;headerPrefix=A0(null);_iconOverrides={};get animationDuration(){return this._animationDuration}set animationDuration(e){/^[0-9]+(?:\.[0-9]+)?$/.test(e)?this._animationDuration=e+"ms":/^[0-9]+(?:\.[0-9]+)?(?:ms|s)$/.test(e)?this._animationDuration=e:this._animationDuration="";}_animationDuration="";_isServer=!T(D).isBrowser;constructor(){super();let t=T(Dr$1).nativeElement.nodeName.toLowerCase();this.orientation=t==="mat-vertical-stepper"?"vertical":"horizontal";}ngAfterContentInit(){super.ngAfterContentInit(),this._icons.forEach(({name:e,templateRef:t})=>this._iconOverrides[e]=t),this.steps.changes.pipe(Bl$1(this._destroyed)).subscribe(()=>this._stateChanged()),this.selectedIndexChange.pipe(Bl$1(this._destroyed)).subscribe(()=>{let e=this._getAnimationDuration();e==="0ms"||e==="0s"?this._onAnimationDone():this._isAnimating.set(true);}),this._ngZone.runOutsideAngular(()=>{this._animationsDisabled||setTimeout(()=>{this._elementRef.nativeElement.classList.add("mat-stepper-animations-enabled"),this._cleanupTransition=this._renderer.listen(this._elementRef.nativeElement,"transitionend",this._handleTransitionend);},200);});}ngAfterViewInit(){if(super.ngAfterViewInit(),typeof queueMicrotask=="function"){let e=false;this._animatedContainers.changes.pipe(Hl$1(null),Bl$1(this._destroyed)).subscribe(()=>queueMicrotask(()=>{e||(e=true,this.animationDone.emit()),this._stateChanged();}));}}ngOnDestroy(){super.ngOnDestroy(),this._cleanupTransition?.();}_getAnimationDuration(){return this._animationsDisabled?"0ms":this.animationDuration?this.animationDuration:this.orientation==="horizontal"?"500ms":"225ms"}_handleTransitionend=e=>{let t=e.target;if(!t)return;let i=this.orientation==="horizontal"&&e.propertyName==="transform"&&t.classList.contains("mat-horizontal-stepper-content-current"),a=this.orientation==="vertical"&&e.propertyName==="grid-template-rows"&&t.classList.contains("mat-vertical-content-container-active");(i||a)&&this._animatedContainers.find(c=>c.nativeElement===t)&&this._onAnimationDone();};_onAnimationDone(){this._isAnimating.set(false),this.animationDone.emit();}static \u0275fac=function(t){return new(t||n)};static \u0275cmp=TE({type:n,selectors:[["mat-stepper"],["mat-vertical-stepper"],["mat-horizontal-stepper"],["","matStepper",""]],contentQueries:function(t,i,a){if(t&1&&ih$1(a,Tp,5)(a,Ip,5),t&2){let o;vD(o=ID())&&(i._steps=o),vD(o=ID())&&(i._icons=o);}},viewQuery:function(t,i){if(t&1&&sh$1(Pr,5)(up,5),t&2){let a;vD(a=ID())&&(i._stepHeader=a),vD(a=ID())&&(i._animatedContainers=a);}},hostVars:14,hostBindings:function(t,i){t&2&&(dh$1("--mat-stepper-animation-duration",i._getAnimationDuration()),fh$1("mat-stepper-horizontal",i.orientation==="horizontal")("mat-stepper-vertical",i.orientation==="vertical")("mat-stepper-label-position-end",i.orientation==="horizontal"&&i.labelPosition=="end")("mat-stepper-label-position-bottom",i.orientation==="horizontal"&&i.labelPosition=="bottom")("mat-stepper-header-position-bottom",i.headerPosition==="bottom")("mat-stepper-animating",i._isAnimating()));},inputs:{disableRipple:"disableRipple",color:"color",labelPosition:"labelPosition",headerPosition:"headerPosition",ariaLabel:[0,"aria-label","ariaLabel"],headerPrefix:[1,"headerPrefix"],animationDuration:"animationDuration"},outputs:{animationDone:"animationDone"},exportAs:["matStepper","matVerticalStepper","matHorizontalStepper"],features:[JD([{provide:Wn,useExisting:n}]),Hp$1],ngContentSelectors:Sl,decls:7,vars:2,consts:[["stepTemplate",""],["horizontalStepsTemplate",""],["animatedContainer",""],[1,"mat-horizontal-stepper-wrapper"],[1,"mat-vertical-stepper-wrapper"],[1,"mat-horizontal-stepper-header-wrapper"],[3,"ngTemplateOutlet","ngTemplateOutletContext"],[1,"mat-horizontal-content-container"],["role","tabpanel",1,"mat-horizontal-stepper-content",3,"id","class"],[3,"ngTemplateOutlet"],["role","tabpanel",1,"mat-horizontal-stepper-content",3,"id"],[1,"mat-step"],[1,"mat-vertical-content-container"],["role","region",1,"mat-vertical-stepper-content",3,"id"],[1,"mat-vertical-content"],[3,"click","keydown","tabIndex","id","index","state","label","selected","active","optional","errorMessage","iconOverrides","disableRipple","color"],["aria-orientation","horizontal","role","tablist",1,"mat-horizontal-stepper-header-container"],[1,"mat-stepper-horizontal-line"]],template:function(t,i){if(t&1&&(gD(),QE(0,_p,1,0),QE(1,vp,6,1,"div",3)(2,wp,4,1,"div",4),$p(3,kp,1,27,"ng-template",null,0,uw)(5,Mp,3,1,"ng-template",null,1,uw)),t&2){let a;ZE(i._isServer?0:-1),Wv(),ZE((a=i.orientation)==="horizontal"?1:a==="vertical"?2:-1);}},dependencies:[jn$1,Pr],styles:[`.mat-stepper-vertical,
.mat-stepper-horizontal {
  display: block;
  font-family: var(--mat-stepper-container-text-font, var(--mat-sys-body-medium-font));
  background: var(--mat-stepper-container-color, var(--mat-sys-surface));
}

.mat-horizontal-stepper-header-wrapper {
  align-items: center;
  display: flex;
}

.mat-horizontal-stepper-header-container {
  white-space: nowrap;
  display: flex;
  align-items: center;
  flex-grow: 1;
}
.mat-stepper-label-position-bottom .mat-horizontal-stepper-header-container {
  align-items: flex-start;
}
.mat-stepper-header-position-bottom .mat-horizontal-stepper-header-container {
  order: 1;
}

.mat-stepper-horizontal-line {
  border-top-width: 1px;
  border-top-style: solid;
  flex: auto;
  height: 0;
  margin: 0 -16px;
  min-width: 32px;
  border-top-color: var(--mat-stepper-line-color, var(--mat-sys-outline));
}
.mat-stepper-label-position-bottom .mat-stepper-horizontal-line {
  margin: 0;
  min-width: 0;
  position: relative;
  top: calc(calc((var(--mat-stepper-header-height, 72px) - 24px) / 2) + 12px);
}

.mat-stepper-label-position-bottom .mat-horizontal-stepper-header:not(:first-child)::before, [dir=rtl] .mat-stepper-label-position-bottom .mat-horizontal-stepper-header:not(:last-child)::before, .mat-stepper-label-position-bottom .mat-horizontal-stepper-header:not(:last-child)::after, [dir=rtl] .mat-stepper-label-position-bottom .mat-horizontal-stepper-header:not(:first-child)::after {
  border-top-width: 1px;
  border-top-style: solid;
  content: "";
  display: inline-block;
  height: 0;
  position: absolute;
  width: calc(50% - 20px);
}

.mat-horizontal-stepper-header {
  display: flex;
  overflow: hidden;
  align-items: center;
  padding: 0 24px;
  height: var(--mat-stepper-header-height, 72px);
}
.mat-horizontal-stepper-header .mat-step-icon {
  margin-right: 8px;
  flex: none;
}
[dir=rtl] .mat-horizontal-stepper-header .mat-step-icon {
  margin-right: 0;
  margin-left: 8px;
}
.mat-horizontal-stepper-header.mat-step-header-empty-label .mat-step-icon {
  margin: 0;
}
.mat-horizontal-stepper-header::before, .mat-horizontal-stepper-header::after {
  border-top-color: var(--mat-stepper-line-color, var(--mat-sys-outline));
}
.mat-stepper-label-position-bottom .mat-horizontal-stepper-header {
  padding: calc((var(--mat-stepper-header-height, 72px) - 24px) / 2) 24px;
}
.mat-stepper-label-position-bottom .mat-horizontal-stepper-header::before, .mat-stepper-label-position-bottom .mat-horizontal-stepper-header::after {
  top: calc(calc((var(--mat-stepper-header-height, 72px) - 24px) / 2) + 12px);
}
.mat-stepper-label-position-bottom .mat-horizontal-stepper-header {
  box-sizing: border-box;
  flex-direction: column;
  height: auto;
}
.mat-stepper-label-position-bottom .mat-horizontal-stepper-header:not(:last-child)::after, [dir=rtl] .mat-stepper-label-position-bottom .mat-horizontal-stepper-header:not(:first-child)::after {
  right: 0;
}
.mat-stepper-label-position-bottom .mat-horizontal-stepper-header:not(:first-child)::before, [dir=rtl] .mat-stepper-label-position-bottom .mat-horizontal-stepper-header:not(:last-child)::before {
  left: 0;
}
[dir=rtl] .mat-stepper-label-position-bottom .mat-horizontal-stepper-header:last-child::before, [dir=rtl] .mat-stepper-label-position-bottom .mat-horizontal-stepper-header:first-child::after {
  display: none;
}
.mat-stepper-label-position-bottom .mat-horizontal-stepper-header .mat-step-icon {
  margin-right: 0;
  margin-left: 0;
}
.mat-stepper-label-position-bottom .mat-horizontal-stepper-header .mat-step-label {
  padding: 16px 0 0 0;
  text-align: center;
  width: 100%;
}

.mat-vertical-stepper-header {
  display: flex;
  align-items: center;
  height: 24px;
  padding: calc((var(--mat-stepper-header-height, 72px) - 24px) / 2) 24px;
}
.mat-vertical-stepper-header .mat-step-icon {
  margin-right: 12px;
}
[dir=rtl] .mat-vertical-stepper-header .mat-step-icon {
  margin-right: 0;
  margin-left: 12px;
}

.mat-horizontal-stepper-wrapper {
  display: flex;
  flex-direction: column;
}

.mat-horizontal-stepper-content {
  visibility: hidden;
  overflow: hidden;
  outline: 0;
  height: 0;
}
.mat-stepper-animations-enabled .mat-horizontal-stepper-content {
  transition: transform var(--mat-stepper-animation-duration, 0) cubic-bezier(0.35, 0, 0.25, 1);
}
.mat-horizontal-stepper-content.mat-horizontal-stepper-content-previous {
  transform: translate3d(-100%, 0, 0);
}
.mat-horizontal-stepper-content.mat-horizontal-stepper-content-next {
  transform: translate3d(100%, 0, 0);
}
.mat-horizontal-stepper-content.mat-horizontal-stepper-content-current {
  visibility: visible;
  transform: none;
  height: auto;
}
.mat-stepper-horizontal:not(.mat-stepper-animating) .mat-horizontal-stepper-content.mat-horizontal-stepper-content-current {
  overflow: visible;
}

.mat-horizontal-content-container {
  overflow: hidden;
  padding: 0 24px 24px 24px;
}
@media (forced-colors: active) {
  .mat-horizontal-content-container {
    outline: solid 1px;
  }
}
.mat-stepper-header-position-bottom .mat-horizontal-content-container {
  padding: 24px 24px 0 24px;
}

.mat-vertical-content-container {
  display: grid;
  grid-template-rows: 0fr;
  grid-template-columns: 100%;
  margin-left: 36px;
  border: 0;
  position: relative;
}
.mat-stepper-animations-enabled .mat-vertical-content-container {
  transition: grid-template-rows var(--mat-stepper-animation-duration, 0) cubic-bezier(0.4, 0, 0.2, 1);
}
.mat-vertical-content-container.mat-vertical-content-container-active {
  grid-template-rows: 1fr;
}
.mat-step:last-child .mat-vertical-content-container {
  border: none;
}
@media (forced-colors: active) {
  .mat-vertical-content-container {
    outline: solid 1px;
  }
}
[dir=rtl] .mat-vertical-content-container {
  margin-left: 0;
  margin-right: 36px;
}
@supports not (grid-template-rows: 0fr) {
  .mat-vertical-content-container {
    height: 0;
  }
  .mat-vertical-content-container.mat-vertical-content-container-active {
    height: auto;
  }
}

.mat-stepper-vertical-line::before {
  content: "";
  position: absolute;
  left: 0;
  border-left-width: 1px;
  border-left-style: solid;
  border-left-color: var(--mat-stepper-line-color, var(--mat-sys-outline));
  top: calc(8px - calc((var(--mat-stepper-header-height, 72px) - 24px) / 2));
  bottom: calc(8px - calc((var(--mat-stepper-header-height, 72px) - 24px) / 2));
}
[dir=rtl] .mat-stepper-vertical-line::before {
  left: auto;
  right: 0;
}

.mat-vertical-stepper-content {
  overflow: hidden;
  outline: 0;
  visibility: hidden;
}
.mat-stepper-animations-enabled .mat-vertical-stepper-content {
  transition: visibility var(--mat-stepper-animation-duration, 0) linear;
}
.mat-vertical-content-container-active > .mat-vertical-stepper-content {
  visibility: visible;
}

.mat-vertical-content {
  padding: 0 24px 24px 24px;
}
`],encapsulation:2})}return n})();var Vr=(()=>{class n{static \u0275fac=function(t){return new(t||n)};static \u0275mod=bE({type:n});static \u0275inj=cu$1({providers:[nf],imports:[et,Ml,Pi,Ye,Ap,Pr,jo$1]})}return n})();var Rp=["panelTemplate"],Fp=(n,r)=>r.value;function Op(n,r){if(n&1){let e=iD();yi(0,"mat-option",3),nh$1("onSelectionChange",function(i){ku(e);let a=pD(2);return Ou(a._selectValue(i.source))}),UD(1),jc$1();}if(n&2){let e=r.$implicit;Zp("value",e.value),Wv(),Ih$1(e.label);}}function Pp(n,r){if(n&1){let e=iD();yi(0,"div",1),nh$1("animationend",function(i){ku(e);let a=pD();return Ou(a._handleAnimationEnd(i))}),JE(1,Op,2,2,"mat-option",2,Fp),jc$1();}if(n&2){let e=pD();fh$1("mat-timepicker-panel-animations-enabled",!e._animationsDisabled)("mat-timepicker-panel-exit",!e.isOpen()),Zp("id",e.panelId),Qp("aria-label",e.ariaLabel()||null)("aria-labelledby",e._getAriaLabelledby()),Wv(),XE(e._timeOptions);}}var Vp=[[["","matTimepickerToggleIcon",""]]],Lp=["[matTimepickerToggleIcon]"];function Np(n,r){n&1&&(Gu(),yi(0,"svg",1),Yp(1,"path",2),jc$1());}var Bp=/^(\d*\.?\d+)\s*(h|hour|hours|m|min|minute|minutes|s|second|seconds)?$/i,Al=new x("MAT_TIMEPICKER_CONFIG");function Tl(n){let r;if(n===null)return null;if(typeof n=="number")r=n;else {if(n.trim().length===0)return null;let e=n.match(Bp),t=e?parseFloat(e[1]):null,i=e?.[2]?.toLowerCase()||null;if(!e||t===null||isNaN(t))return null;i==="h"||i==="hour"||i==="hours"?r=t*3600:i==="m"||i==="min"||i==="minute"||i==="minutes"?r=t*60:r=t;}return r}function zp(n,r,e,t,i){i=Math.max(i,1);let a=[],o=n.compareTime(e,t)<1?e:t;for(;n.sameDate(o,e)&&n.compareTime(o,t)<1&&n.isValid(o);)a.push({value:o,label:n.format(o,r.display.timeOptionLabel)}),o=n.addSeconds(o,i);return a}var Hp=new x("MAT_TIMEPICKER_SCROLL_STRATEGY",{providedIn:"root",factory:()=>{let n=T(ge);return ()=>gi$1(n)}}),jp=(()=>{class n{_dir=T(Er$2,{optional:true});_viewContainerRef=T(Ai$1);_injector=T(ge);_defaultConfig=T(Al,{optional:true});_dateAdapter=T(Xe$1,{optional:true});_dateFormats=T(Ur,{optional:true});_scrollStrategyFactory=T(Hp);_animationsDisabled=B();_isOpen=Vo$1(false);_activeDescendant=Vo$1(null);_input=Vo$1(null);_overlayRef=null;_portal=null;_optionsCacheKey=null;_localeChanges;_onOpenRender=null;_panelTemplate=R0.required("panelTemplate");_timeOptions=[];_options=k0();_keyManager=new ei(this._options,this._injector).withHomeAndEnd(true).withPageUpDown(true).withVerticalOrientation(true);interval=A0(Tl(this._defaultConfig?.interval||null),{transform:Tl});options=A0(null);isOpen=this._isOpen.asReadonly();selected=x0();opened=x0();closed=x0();activeDescendant=this._activeDescendant.asReadonly();panelId=T(Y).getId("mat-timepicker-panel-");disableRipple=A0(this._defaultConfig?.disableRipple??false,{transform:j0});ariaLabel=A0(null,{alias:"aria-label"});ariaLabelledby=A0(null,{alias:"aria-labelledby"});disabled=gw(()=>!!this._input()?.disabled());panelClass=A0();constructor(){T(Dr$1).nativeElement.setAttribute("mat-timepicker-panel-id",this.panelId),this._handleLocaleChanges(),this._handleInputStateChanges(),this._keyManager.change.subscribe(()=>this._activeDescendant.set(this._keyManager.activeItem?.id||null));}open(){let e=this._input();if(!e||(e.focus(),this._isOpen()))return;this._isOpen.set(true),this._generateOptions();let t=this._getOverlayRef();t.updateSize({width:e.getOverlayOrigin().nativeElement.offsetWidth}),this._portal??=new at$1(this._panelTemplate(),this._viewContainerRef),t.hasAttached()||t.attach(this._portal),this._onOpenRender?.destroy(),this._onOpenRender=Dv(()=>{let i=this._options();this._syncSelectedState(e.value(),i,i[0]),this._onOpenRender=null;},{injector:this._injector}),this.opened.emit();}close(){this._isOpen()&&(this._isOpen.set(false),this.closed.emit(),this._animationsDisabled&&this._overlayRef?.detach());}registerInput(e){this._input();this._input.set(e);}ngOnDestroy(){this._keyManager.destroy(),this._localeChanges?.unsubscribe(),this._onOpenRender?.destroy(),this._overlayRef?.dispose();}_getOverlayHost(){return this._overlayRef?.hostElement}_selectValue(e){this.close(),this._keyManager.setActiveItem(e),this._options().forEach(t=>{t!==e&&t.deselect(false);}),this._input()?.timepickerValueAssigned(e.value),this.selected.emit({value:e.value,source:this}),this._input()?.focus();}_getAriaLabelledby(){return this.ariaLabel()?null:this.ariaLabelledby()||this._input()?.getLabelId()||null}_handleAnimationEnd(e){e.animationName==="_mat-timepicker-exit"&&this._overlayRef?.detach();}_getOverlayRef(){if(this._overlayRef)return this._overlayRef;let e=vi(this._injector,this._input().getOverlayOrigin()).withFlexibleDimensions(false).withPush(false).withTransformOriginOn(".mat-timepicker-panel").withPopoverLocation("inline").withPositions([{originX:"start",originY:"bottom",overlayX:"start",overlayY:"top"},{originX:"start",originY:"top",overlayX:"start",overlayY:"bottom",panelClass:"mat-timepicker-above"}]);return this._overlayRef=Zt(this._injector,{positionStrategy:e,scrollStrategy:this._scrollStrategyFactory(),direction:this._dir||"ltr",hasBackdrop:false,disableAnimations:this._animationsDisabled,panelClass:this.panelClass()}),this._overlayRef.detachments().subscribe(()=>this.close()),this._overlayRef.keydownEvents().subscribe(t=>this._handleKeydown(t)),this._overlayRef.outsidePointerEvents().subscribe(t=>{let i=H(t),a=this._input()?.getOverlayOrigin().nativeElement;i&&a&&i!==a&&!a.contains(i)&&this.close();}),this._overlayRef}_generateOptions(){let e=this.interval()??1800,t=this.options();if(t!==null)this._timeOptions=t;else {let i=this._input(),a=this._dateAdapter,o=this._dateFormats.display.timeInput,c=i?.min()||a.setTime(a.today(),0,0,0),y=i?.max()||a.setTime(a.today(),23,59,0),F=e+"/"+a.format(c,o)+"/"+a.format(y,o);F!==this._optionsCacheKey&&(this._optionsCacheKey=F,this._timeOptions=zp(a,this._dateFormats,c,y,e));}}_syncSelectedState(e,t,i){let a=false;for(let o of t)e&&this._dateAdapter.sameTime(o.value,e)?(o.select(false),Lr(o,"center"),mw(()=>this._keyManager.setActiveItem(o)),a=true):o.deselect(false);a||(i?(mw(()=>this._keyManager.setActiveItem(i)),Lr(i,"center")):mw(()=>this._keyManager.setActiveItem(-1)));}_handleKeydown(e){let t=e.keyCode;if(t===9)this.close();else if(t===27&&!$(e))e.preventDefault(),this.close();else if(t===13)e.preventDefault(),this._keyManager.activeItem?this._selectValue(this._keyManager.activeItem):this.close();else {let i=this._keyManager.activeItem;this._keyManager.onKeydown(e);let a=this._keyManager.activeItem;a&&a!==i&&Lr(a,"nearest");}}_handleLocaleChanges(){this._localeChanges=this._dateAdapter.localeChanges.subscribe(()=>{this._optionsCacheKey=null,this.isOpen()&&this._generateOptions();});}_handleInputStateChanges(){nd$1(()=>{let e=this._input(),t=this._options();this._isOpen()&&e&&this._syncSelectedState(e.value(),t,null);});}static \u0275fac=function(t){return new(t||n)};static \u0275cmp=TE({type:n,selectors:[["mat-timepicker"]],viewQuery:function(t,i){t&1&&ch$1(i._panelTemplate,Rp,5)(i._options,sr$1,5),t&2&&ED(2);},inputs:{interval:[1,"interval"],options:[1,"options"],disableRipple:[1,"disableRipple"],ariaLabel:[1,"aria-label","ariaLabel"],ariaLabelledby:[1,"aria-labelledby","ariaLabelledby"],panelClass:[1,"panelClass"]},outputs:{selected:"selected",opened:"opened",closed:"closed"},exportAs:["matTimepicker"],features:[JD([{provide:qa$1,useExisting:n}])],decls:2,vars:0,consts:[["panelTemplate",""],["role","listbox",1,"mat-timepicker-panel",3,"animationend","id"],[3,"value"],[3,"onSelectionChange","value"]],template:function(t,i){t&1&&$p(0,Pp,3,7,"ng-template",null,0,uw);},dependencies:[sr$1],styles:[`@keyframes _mat-timepicker-enter {
  from {
    opacity: 0;
    transform: scaleY(0.8);
  }
  to {
    opacity: 1;
    transform: none;
  }
}
@keyframes _mat-timepicker-exit {
  from {
    opacity: 1;
  }
  to {
    opacity: 0;
  }
}
mat-timepicker {
  display: none;
}

.mat-timepicker-panel {
  width: 100%;
  max-height: 256px;
  transform-origin: center top;
  overflow: auto;
  padding: 8px 0;
  box-sizing: border-box;
  position: relative;
  border-bottom-left-radius: var(--mat-timepicker-container-shape, var(--mat-sys-corner-extra-small));
  border-bottom-right-radius: var(--mat-timepicker-container-shape, var(--mat-sys-corner-extra-small));
  box-shadow: var(--mat-timepicker-container-elevation-shadow, 0px 3px 1px -2px rgba(0, 0, 0, 0.2), 0px 2px 2px 0px rgba(0, 0, 0, 0.14), 0px 1px 5px 0px rgba(0, 0, 0, 0.12));
  background-color: var(--mat-timepicker-container-background-color, var(--mat-sys-surface-container));
}
@media (forced-colors: active) {
  .mat-timepicker-panel {
    outline: solid 1px;
  }
}
.mat-timepicker-above .mat-timepicker-panel {
  border-bottom-left-radius: 0;
  border-bottom-right-radius: 0;
  border-top-left-radius: var(--mat-timepicker-container-shape, var(--mat-sys-corner-extra-small));
  border-top-right-radius: var(--mat-timepicker-container-shape, var(--mat-sys-corner-extra-small));
}

.mat-timepicker-panel-animations-enabled {
  animation: _mat-timepicker-enter 120ms cubic-bezier(0, 0, 0.2, 1);
}
.mat-timepicker-panel-animations-enabled.mat-timepicker-panel-exit {
  animation: _mat-timepicker-exit 100ms linear;
}

.mat-timepicker-input[readonly] {
  cursor: pointer;
}

@media (forced-colors: active) {
  .mat-timepicker-toggle-default-icon {
    color: CanvasText;
  }
}
`],encapsulation:2})}return n})();function Lr(n,r){n._getHostElement().scrollIntoView({block:r,inline:r});}var Gp=(()=>{class n{_defaultConfig=T(Al,{optional:true});_defaultTabIndex=(()=>{let e=T(new Ah$1("tabindex"),{optional:true}),t=Number(e);return isNaN(t)?null:t})();_isDisabled=gw(()=>{let e=this.timepicker();return this.disabled()||e.disabled()});timepicker=A0.required({alias:"for"});ariaLabel=A0(void 0,{alias:"aria-label"});ariaLabelledby=A0(void 0,{alias:"aria-labelledby"});_defaultAriaLabel="Open timepicker options";disabled=A0(false,{transform:j0,alias:"disabled"});tabIndex=A0(this._defaultTabIndex);disableRipple=A0(this._defaultConfig?.disableRipple??false,{transform:j0});_open(e){this.timepicker()&&!this._isDisabled()&&(this.timepicker().open(),e.stopPropagation());}getAriaLabel(){return this.ariaLabelledby()?null:this.ariaLabel()||this._defaultAriaLabel}static \u0275fac=function(t){return new(t||n)};static \u0275cmp=TE({type:n,selectors:[["mat-timepicker-toggle"]],hostAttrs:[1,"mat-timepicker-toggle"],hostVars:1,hostBindings:function(t,i){t&1&&nh$1("click",function(o){return i._open(o)}),t&2&&Qp("tabindex",null);},inputs:{timepicker:[1,"for","timepicker"],ariaLabel:[1,"aria-label","ariaLabel"],ariaLabelledby:[1,"aria-labelledby","ariaLabelledby"],disabled:[1,"disabled"],tabIndex:[1,"tabIndex"],disableRipple:[1,"disableRipple"]},exportAs:["matTimepickerToggle"],ngContentSelectors:Lp,decls:3,vars:6,consts:[["matIconButton","","type","button","aria-haspopup","listbox",3,"tabIndex","disabled","disableRipple"],["height","24px","width","24px","viewBox","0 -960 960 960","fill","currentColor","focusable","false","aria-hidden","true",1,"mat-timepicker-toggle-default-icon"],["d","m612-292 56-56-148-148v-184h-80v216l172 172ZM480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-400Zm0 320q133 0 226.5-93.5T800-480q0-133-93.5-226.5T480-800q-133 0-226.5 93.5T160-480q0 133 93.5 226.5T480-160Z"]],template:function(t,i){t&1&&(gD(Vp),yi(0,"button",0),mD(1,0,null,Np,2,0),jc$1()),t&2&&(Zp("tabIndex",i._isDisabled()?-1:i.tabIndex())("disabled",i._isDisabled())("disableRipple",i.disableRipple()),Qp("aria-label",i.getAriaLabel())("aria-labelledby",i.ariaLabelledby())("aria-expanded",i.timepicker().isOpen()));},dependencies:[Ss$1],encapsulation:2})}return n})(),Nr=(()=>{class n{static \u0275fac=function(t){return new(t||n)};static \u0275mod=bE({type:n});static \u0275inj=cu$1({imports:[jp,Gp,di]})}return n})();var Br=(()=>{class n{static \u0275fac=function(t){return new(t||n)};static \u0275mod=bE({type:n});static \u0275inj=cu$1({imports:[jo$1]})}return n})();var Rl=(()=>{class n{static \u0275fac=function(t){return new(t||n)};static \u0275mod=bE({type:n});static \u0275inj=cu$1({})}return n})();var zr=(()=>{class n{static \u0275fac=function(t){return new(t||n)};static \u0275mod=bE({type:n});static \u0275inj=cu$1({imports:[Rl,jo$1]})}return n})();var Fl=[Ea,cr,Xr,Br$1,mr,L,Ra,hr,zs$1,Wr,js$1,Mi$1,Ye,Dr,Tr$1,Oi,Pa,Ot,Mr,Pi,Va,Sr,za,ja,Ga,Ua,Wa,Vi,Ir,Er,Tr,jr,Ar,Vr,ir,dr,Nr,Br,Li,zr],Hr=class n{static \u0275fac=function(e){return new(e||n)};static \u0275mod=bE({type:n});static \u0275inj=cu$1({imports:[Fl,Ea,cr,Xr,Br$1,mr,L,Ra,hr,zs$1,Wr,js$1,Mi$1,Ye,Dr,Tr$1,Oi,Pa,Ot,Mr,Pi,Va,Sr,za,ja,Ga,Ua,Wa,Vi,Ir,Er,Tr,jr,Ar,Vr,ir,dr,Nr,Br,Li,zr]})};export{Ma as $,Ai as A,By as B,Nf as C,Da as D,En as E,Fn as F,Gt as G,Hr as H,Ic as I,Js as J,Ks as K,Ly as L,Lf as M,Na as N,Ea as O,Py as P,Qd as Q,c_ as R,Sc as S,nc as T,Uy as U,Vy as V,Ws as W,yh as X,_d as Y,Zl as Z,_l as _,Gg as a,$l as a0,Cv as a1,K_ as a2,mu as a3,Xe as a4,Pa as a5,Bf as a6,za as a7,Sn as a8,us as a9,_s as aa,Em as b,cu as c,du as d,dh as e,fb as f,gd as g,hu as h,gm as i,jo as j,Hy as k,Ny as l,jy as m,Gy as n,ou as o,lc as p,Wd as q,Pv as r,su as s,pi as t,un as u,Fi as v,td as w,xd as x,y0 as y,zy as z};