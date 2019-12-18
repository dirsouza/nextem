import { configure, localize, extend } from "vee-validate";
import ptBR from "vee-validate/dist/locale/pt_BR";
import * as rules from "vee-validate/dist/rules";

Object.keys(rules).forEach(rule => extend(rule, rules[rule]));

localize("pt_BR", ptBR);

configure({
  classes: {
    valid: "is-valid",
    invalid: "is-invalid"
  },
  bails: true,
  skipOptional: true,
  mode: "lazy",
  useConstraintAttrs: true
});
