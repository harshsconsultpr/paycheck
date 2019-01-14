<?php

/**
 * @file
 * Contains \Drupal\miniorange_saml\Form\MiniorangeSamlCustomerSetup.
 */

namespace Drupal\miniorange_saml_idp\Form;

use Drupal\miniorange_saml_idp\Utilities;
use Drupal\miniorange_saml_idp\MiniorangeSAMLCustomer;
use Drupal\miniorange_saml_idp\AESEncryption;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element;
use Symfony\Component\HttpFoundation\RedirectResponse;

class MiniorangeSamlCustomerSetup extends FormBase {

    public function getFormId() {
        return 'miniorange_saml_customer_setup';
    }

    public function buildForm(array $form, \Drupal\Core\Form\FormStateInterface $form_state) {

        if (!($_SERVER["\122\x45\x51\x55\105\123\124\137\x55\x52\111"] != "\57\141\144\155\151\156\x2f\143\x6f\x6e\x66\x69\147\x2f\x70\145\x6f\160\x6c\x65\x2f\155\151\156\x69\157\162\141\x6e\147\x65\137\163\141\155\154\x5f\151\x64\x70\57\x63\x75\x73\164\157\155\x65\162\x5f\x73\x65\x74\x75\x70" && $_SERVER["\x52\105\x51\125\x45\123\124\137\125\122\111"] != "\x2f\141\144\x6d\x69\x6e\57\143\157\x6e\146\151\147\57\160\x65\x6f\160\154\x65\57\x6d\x69\156\x69\x6f\x72\141\156\x67\145\x5f\x73\141\x6d\x6c\x5f\151\144\160\57\143\165\x73\164\x6f\x6d\x65\162\x5f\163\x65\x74\165\x70")) {
            goto SP;
        }
        echo "\x54\x68\151\x73\x20\151\163\x20\141\x20\x64\157\155\x61\x69\156\x20\x73\x70\145\x63\151\x66\x69\x63\40\x6c\x69\143\145\156\163\145\x2c\40\x70\154\x65\141\x73\145\x20\x75\163\145\40\151\x74\40\x6f\156\40\x74\150\145\40\x63\157\162\x72\x65\x63\x74\40\144\157\155\141\x69\x6e";
        die;
        SP:
        if (!($_SERVER["\122\105\121\x55\105\x53\124\137\x55\122\x49"] == "\57\141\144\155\x69\x6e\x2f\x63\157\x6e\x66\x69\147\57\160\x65\x6f\x70\x6c\x65\57\x6d\x69\156\151\157\x72\x61\x6e\x67\x65\x5f\163\x61\155\x6c\x5f\x69\144\x70\x2f\x63\x75\163\x74\x6f\x6d\x65\162\137\163\145\x74\x75\160" || $_SERVER["\122\105\121\125\105\x53\124\x5f\125\x52\111"] == "\x2f\x61\144\155\151\x6e\57\143\157\x6e\x66\x69\x67\x2f\x70\145\x6f\x70\x6c\145\x2f\155\x69\156\x69\157\x72\x61\x6e\x67\x65\137\x73\141\155\x6c\137\151\x64\x70\x2f\143\x75\163\x74\x6f\155\x65\162\137\x73\145\x74\165\x70")) {
            goto wV;
        }
        \Drupal::configFactory()->getEditable("\x6d\x69\x6e\151\x6f\x72\x61\x6e\x67\145\x5f\x73\x61\x6d\x6c\x5f\x69\x64\x70\56\163\x65\164\x74\151\156\x67\x73")->set("\155\151\x6e\151\157\162\141\156\147\145\137\163\141\x6d\154\x5f\163\164\141\x74\165\x73", "\120\114\125\x47\x49\x4e\x5f\x43\x4f\116\106\111\107\x55\122\x41\124\x49\117\116")->save();
        \Drupal::configFactory()->getEditable("\155\151\156\151\x6f\x72\x61\156\x67\x65\137\163\141\x6d\x6c\137\x69\144\160\x2e\x73\x65\164\x74\151\156\x67\x73")->set("\x6d\151\x6e\x69\157\162\x61\x6e\x67\145\x5f\163\x61\155\154\x5f\x6c\151\x63\x65\156\x73\x65\137\153\x65\171", "\x48\x4b\60\127\x32\104\x46\70\130\131\x31\x51\116\x30\x48")->save();
        \Drupal::configFactory()->getEditable("\155\151\x6e\151\157\x72\141\x6e\x67\145\137\163\x61\x6d\x6c\137\151\x64\160\x2e\x73\145\164\x74\151\x6e\x67\x73")->set("\155\151\x6e\151\x6f\162\x61\x6e\147\x65\x5f\163\x61\155\x6c\x5f\143\x75\163\x74\157\155\x65\x72\137\141\144\155\x69\x6e\137\145\155\x61\x69\154", "\144\165\x6e\x63\x61\156\x66\100\166\x65\164\x6d\145\x64\x61\143\141\144\145\x6d\171\56\157\x72\x67")->save();
        \Drupal::configFactory()->getEditable("\x6d\x69\x6e\151\x6f\x72\x61\x6e\x67\145\137\x73\141\x6d\x6c\137\x69\x64\x70\56\x73\145\164\x74\x69\156\x67\163")->set("\x6d\x69\x6e\x69\157\x72\141\156\147\145\137\163\x61\155\x6c\x5f\x63\x75\163\x74\157\x6d\x65\x72\x5f\151\x64", "\x31\x32\67\x31\x37\71")->save();
        \Drupal::configFactory()->getEditable("\x6d\x69\156\x69\157\x72\141\156\x67\x65\137\x73\x61\x6d\154\137\151\144\160\56\x73\x65\164\164\151\x6e\x67\163")->set("\155\x69\x6e\151\x6f\162\x61\x6e\x67\x65\x5f\x73\x61\x6d\x6c\x5f\143\165\x73\x74\157\x6d\x65\162\x5f\x61\144\x6d\151\156\137\x74\x6f\153\145\x6e", "\121\162\x36\x50\151\141\x66\101\x4d\144\142\x47\151\62\x35\60")->save();
        \Drupal::configFactory()->getEditable("\x6d\151\x6e\151\x6f\162\x61\156\147\145\x5f\163\x61\x6d\154\x5f\x69\x64\x70\x2e\163\x65\164\164\x69\x6e\147\x73")->set("\x6d\151\156\x69\157\x72\x61\x6e\147\x65\137\163\x61\x6d\x6c\137\x63\165\x73\x74\x6f\155\x65\x72\x5f\x61\160\x69\137\153\145\171", "\x4c\117\x47\157\x53\x59\x30\106\112\x43\x44\x50\152\x52\x45\166\x5a\x4a\x46\63\x6c\142\122\x59\x7a\x6b\x75\x47\x49\x6f\130\70")->save();
        wV:
        $Ac = \Drupal::config("\x6d\x69\x6e\x69\x6f\162\141\x6e\147\145\137\x73\141\x6d\x6c\137\x69\144\160\56\x73\x65\164\164\x69\x6e\147\x73")->get("\155\x69\156\151\x6f\x72\x61\x6e\x67\x65\137\x73\x61\155\154\x5f\163\164\x61\x74\165\x73");
        if ($Ac == "\x50\x4c\125\x47\111\116\x5f\103\x4f\116\106\111\x47\x55\122\x41\x54\x49\x4f\x4e" && \Drupal::config("\155\151\x6e\x69\157\x72\x61\156\147\145\x5f\x73\141\x6d\x6c\137\151\144\x70\56\163\x65\x74\x74\x69\x6e\147\x73")->get("\155\151\x6e\x69\x6f\x72\x61\156\147\x65\137\x73\x61\x6d\x6c\x5f\154\x69\x63\x65\x6e\x73\145\137\153\145\171") == NULL) {
            goto GF;
        }
        if ($Ac == "\x56\x41\x4c\x49\104\101\124\x45\137\117\124\120") {
            goto Wh;
        }
        if ($Ac == "\120\114\x55\107\x49\x4e\x5f\x43\117\116\106\x49\107\125\122\101\x54\111\117\x4e") {
            goto XU;
        }
        goto Jv;
        Wh:
        $AW["\x6d\x69\x6e\151\x6f\x72\x61\x6e\147\145\x5f\163\x61\x6d\154\x5f\143\x75\163\164\157\x6d\145\162\x5f\x6f\x74\160\x5f\x74\x6f\x6b\145\x6e"] = array("\43\164\x79\x70\145" => "\x74\145\x78\x74\x66\151\x65\x6c\144", "\43\x74\151\x74\154\x65" => t("\x4f\x54\120"));
        $AW["\x6d\x69\156\x69\157\x72\x61\x6e\x67\145\137\x73\x61\155\154\x5f\143\165\x73\164\x6f\155\145\162\137\x76\141\x6c\151\144\141\x74\x65\137\x6f\164\x70\x5f\142\165\x74\x74\157\x6e"] = array("\43\x74\171\160\145" => "\163\x75\142\155\151\164", "\43\x76\141\154\x75\145" => t("\126\x61\x6c\151\x64\141\164\145\x20\x4f\x54\120"), "\43\x73\x75\x62\x6d\151\164" => array("\72\72\155\151\156\151\x6f\x72\141\x6e\x67\145\137\163\x61\x6d\x6c\x5f\x69\x64\x70\137\166\141\154\151\144\x61\x74\145\137\x6f\164\160\x5f\x73\165\142\155\x69\164"));
        $AW["\x6d\x69\x6e\151\157\x72\141\156\x67\145\x5f\x73\141\x6d\x6c\x5f\x63\165\x73\x74\x6f\x6d\x65\x72\x5f\x73\145\x74\x75\160\x5f\x72\x65\163\x65\156\x64\157\164\160"] = array("\43\x74\171\x70\x65" => "\x73\165\x62\155\x69\x74", "\43\x76\x61\x6c\x75\x65" => t("\122\145\x73\145\156\144\40\117\x54\x50"), "\43\163\x75\142\155\x69\164" => array("\x3a\x3a\x6d\151\x6e\x69\x6f\162\x61\x6e\147\145\x5f\163\141\x6d\x6c\x5f\151\144\x70\x5f\162\145\x73\145\156\x64\x5f\x6f\164\160"));
        $AW["\x6d\151\156\151\157\162\x61\x6e\x67\x65\137\x73\141\x6d\x6c\137\143\x75\x73\x74\157\x6d\145\162\137\163\x65\x74\165\x70\x5f\x62\141\143\153"] = array("\43\164\171\160\145" => "\x73\165\142\155\x69\164", "\x23\x76\141\x6c\165\145" => t("\102\141\143\x6b"), "\43\163\165\142\155\151\x74" => array("\x3a\x3a\155\151\156\151\157\x72\141\x6e\x67\x65\x5f\163\141\155\154\137\151\144\x70\137\142\141\x63\153"));
        return $AW;
        goto Jv;
        XU:
        $AW["\x6d\141\x72\153\x75\160\137\164\157\x70"] = array("\x23\155\141\x72\x6b\x75\160" => "\x3c\x64\151\166\x3e\x54\x68\x61\156\x6b\x20\171\x6f\165\x20\146\x6f\x72\x20\162\145\x67\151\163\x74\x65\162\151\x6e\x67\40\x77\151\x74\x68\x20\x6d\151\156\151\117\162\x61\x6e\147\x65\x3c\x2f\144\x69\x76\x3e" . "\74\x68\x34\x3e\x59\x6f\x75\162\40\120\162\157\146\x69\154\x65\72\40\74\x2f\150\64\76");
        $Vm = array("\x65\x6d\x61\151\154" => array("\144\141\x74\x61" => t("\103\x75\163\164\x6f\x6d\x65\162\40\x45\155\x61\x69\x6c")), "\143\165\x73\164\157\155\x65\162\151\144" => array("\x64\x61\164\x61" => t("\103\165\163\x74\x6f\155\x65\162\40\x49\104")), "\x74\157\x6b\x65\156" => array("\x64\x61\164\x61" => t("\x54\157\x6b\x65\156\40\x4b\x65\x79")), "\x61\160\151\x6b\x65\171" => array("\x64\141\x74\x61" => t("\x41\x50\111\x20\x4b\x65\x79")));
        $iK = array();
        $iK[0] = array("\145\155\x61\151\x6c" => \Drupal::config("\x6d\151\x6e\x69\157\x72\141\x6e\147\x65\x5f\x73\x61\x6d\154\137\151\x64\160\x2e\x73\145\x74\x74\151\156\147\163")->get("\155\x69\156\x69\x6f\162\x61\x6e\x67\x65\x5f\163\x61\155\x6c\x5f\143\x75\x73\164\157\x6d\x65\162\137\x61\x64\155\x69\156\137\x65\x6d\141\x69\x6c"), "\x63\x75\163\164\157\x6d\x65\162\151\x64" => \Drupal::config("\x6d\x69\x6e\x69\157\162\x61\156\x67\145\137\x73\x61\x6d\x6c\137\151\144\160\x2e\x73\x65\x74\164\151\156\147\x73")->get("\x6d\x69\156\x69\157\x72\141\x6e\x67\x65\x5f\x73\x61\x6d\154\137\143\165\163\164\x6f\155\145\162\x5f\151\x64"), "\164\157\153\x65\x6e" => \Drupal::config("\155\x69\x6e\151\x6f\162\141\x6e\147\145\137\x73\141\155\154\x5f\151\x64\x70\56\x73\145\164\x74\151\156\147\163")->get("\155\x69\156\151\x6f\162\x61\156\147\x65\137\x73\141\155\154\x5f\x63\165\163\164\x6f\x6d\145\x72\137\141\x64\155\x69\156\x5f\x74\157\153\145\x6e"), "\141\160\x69\x6b\x65\x79" => \Drupal::config("\x6d\151\x6e\x69\x6f\x72\x61\x6e\x67\145\x5f\163\141\x6d\x6c\137\x69\144\160\56\163\x65\164\x74\151\156\x67\x73")->get("\x6d\151\x6e\x69\157\x72\141\156\x67\x65\x5f\x73\141\x6d\154\x5f\x63\x75\x73\164\x6f\155\x65\x72\137\x61\x70\x69\137\153\x65\171"));
        $AW["\x66\151\145\154\144\163\145\164"]["\x63\x75\163\x74\x6f\155\145\162\x69\x6e\146\157"] = array("\x23\x74\150\145\155\145" => "\x74\x61\x62\x6c\x65", "\x23\150\x65\x61\144\145\162" => $Vm, "\43\x72\x6f\167\x73" => $iK);
        return $AW;
        Jv:
        $AW["\155\141\x72\153\165\160\x5f\61\x34"] = array("\43\x6d\141\x72\x6b\165\x70" => "\x3c\x68\x33\x3e\x52\x65\147\x69\163\164\x65\x72\40\x77\151\164\x68\x20\x6d\x69\156\151\117\162\141\156\x67\x65\74\57\150\63\76");
        $AW["\155\141\x72\153\165\x70\137\61\65"] = array("\43\155\x61\162\153\165\160" => "\112\x75\163\x74\x20\x63\x6f\x6d\160\x6c\x65\164\145\40\x74\x68\x65\40\x73\x68\x6f\x72\x74\x20\162\145\x67\x69\x73\x74\x72\141\164\151\x6f\156\40\142\145\154\x6f\x77\x20\x74\157\x20\143\157\x6e\x66\x69\x67\165\162\145" . "\40\x74\150\x65\40\x53\101\115\x4c\x20\120\154\x75\x67\x69\156\56\40\120\x6c\145\x61\163\x65\40\145\156\164\x65\162\x20\x61\x20\x76\x61\x6c\x69\144\x20\x65\155\141\151\x6c\40\x69\144\40\74\x62\162\x3e\164\150\x61\x74\x20\x79\157\165\40\150\141\166\145" . "\40\x61\x63\143\145\163\163\x20\x74\157\x2e\x20\x59\x6f\165\40\x77\151\154\154\40\x62\145\40\x61\142\x6c\145\x20\164\157\40\x6d\x6f\166\145\x20\146\x6f\x72\x77\x61\x72\144\x20\x61\x66\x74\145\162\40\166\145\x72\x69\146\x79\151\156\x67\x20\141\x6e\x20\117\124\x50" . "\40\164\150\x61\164\40\167\x65\x20\167\x69\x6c\154\x20\x73\x65\x6e\144\x20\x74\x6f\x20\x74\x68\x69\163\x20\x65\155\x61\151\x6c\56");
        $AW["\155\151\156\151\x6f\162\141\156\x67\145\137\x73\x61\x6d\x6c\x5f\143\x75\163\164\x6f\x6d\145\162\137\x73\x65\x74\x75\160\x5f\x75\163\x65\x72\156\x61\x6d\x65"] = array("\43\164\x79\x70\145" => "\x74\145\x78\x74\146\151\145\154\144", "\43\164\151\x74\x6c\145" => t("\x45\x6d\x61\151\154"), "\x23\162\145\x71\165\151\x72\x65\x64" => TRUE);
        $AW["\155\151\156\151\x6f\162\141\x6e\147\x65\x5f\163\x61\155\x6c\x5f\x63\x75\x73\164\x6f\155\x65\162\137\x73\145\164\165\x70\137\160\150\157\x6e\x65"] = array("\x23\164\x79\160\x65" => "\x74\x65\170\x74\146\x69\145\154\x64", "\43\x74\x69\x74\x6c\145" => t("\x50\x68\x6f\156\x65"));
        $AW["\x6d\x61\x72\x6b\x75\160\137\61\66"] = array("\43\x6d\x61\162\153\165\x70" => "\74\x62\x3e\116\x4f\x54\x45\72\x3c\57\x62\x3e\x20\127\x65\x20\167\151\154\154\40\x6f\156\154\171\40\x63\x61\154\x6c\x20\151\146\x20\171\157\x75\40\156\145\x65\144\40\163\x75\x70\x70\157\162\x74\56");
        $AW["\155\x69\156\151\157\x72\x61\156\x67\x65\137\x73\141\x6d\x6c\x5f\143\x75\163\x74\x6f\155\145\x72\x5f\x73\145\x74\x75\x70\137\x70\141\163\x73\167\x6f\162\144"] = array("\43\164\x79\160\x65" => "\160\x61\x73\x73\x77\157\162\x64\137\143\157\x6e\146\151\162\155", "\x23\x72\145\x71\165\x69\x72\x65\144" => TRUE);
        $AW["\155\x69\156\x69\157\162\x61\156\147\x65\x5f\163\x61\155\154\x5f\143\165\x73\164\157\155\145\162\x5f\163\145\x74\165\x70\x5f\x62\x75\x74\x74\157\156"] = array("\43\164\x79\160\x65" => "\163\x75\142\x6d\x69\x74", "\x23\166\141\154\165\x65" => t("\x52\x65\x67\x69\x73\x74\x65\x72"));
        return $AW;
        goto kW;
        GF:
        $AW["\155\151\x6e\x69\157\162\x61\x6e\147\x65\x5f\x73\x61\155\154\137\x6c\151\143\x65\x6e\163\x65\137\x6b\145\x79"] = array("\43\164\171\x70\x65" => "\x74\145\170\x74\x66\x69\x65\x6c\x64", "\43\164\x69\164\154\145" => t("\x4c\151\143\145\x6e\x73\145\40\113\145\171"), "\x23\141\x74\x74\x72\x69\142\165\x74\145\x73" => array("\x70\154\141\x63\x65\150\x6f\154\x64\x65\162" => "\105\x6e\164\145\162\x20\171\157\x75\x72\x20\154\151\x63\x65\156\163\x65\40\x6b\x65\x79\x20\164\x6f\x20\141\x63\164\151\166\141\x74\145\x20\x74\150\145\x20\x70\154\165\147\x69\156"), "\x23\x72\x65\161\x75\151\162\x65\144" => TRUE);
        $AW["\x6d\151\156\151\157\x72\141\x6e\147\145\137\163\x61\x6d\154\137\x63\165\x73\164\x6f\155\x65\162\137\166\x61\154\x69\144\141\x74\145\x5f\x6c\151\x63\145\x6e\163\x65\x5f\x62\x75\x74\x74\x6f\x6e"] = array("\43\x74\x79\160\x65" => "\163\x75\142\155\x69\x74", "\x23\166\x61\x6c\165\145" => t("\101\x63\x74\151\x76\x61\164\x65\40\x4c\151\143\145\156\163\145"), "\x23\163\165\x62\155\151\x74" => array("\72\72\x6d\151\156\151\157\162\x61\x6e\147\x65\137\163\x61\155\154\137\166\x61\154\x69\144\x61\164\x65\137\154\x69\143\145\156\x73\145\137\x73\165\142\155\x69\164"));
        return $AW;
        kW:
    }

    public function submitForm(array &$form, \Drupal\Core\Form\FormStateInterface $form_state) {



        $username = $form['miniorange_saml_customer_setup_username']['#value'];
        $phone = $form['miniorange_saml_customer_setup_phone']['#value'];
        $password = $form['miniorange_saml_customer_setup_password']['#value']['pass1'];

        $customer_config = new MiniorangeSAMLCustomer($username, $phone, $password, NULL);
        $check_customer_response = json_decode($customer_config->checkCustomer());

        if ($check_customer_response->status == 'CUSTOMER_NOT_FOUND') {

            // Create customer.
            // Store email and phone.
            \Drupal::configFactory()->getEditable('miniorange_saml_idp.settings')->set('miniorange_saml_customer_admin_email', $username)->save();
            \Drupal::configFactory()->getEditable('miniorange_saml_idp.settings')->set('miniorange_saml_customer_admin_phone', $phone)->save();
            \Drupal::configFactory()->getEditable('miniorange_saml_idp.settings')->set('miniorange_saml_customer_admin_password', $password)->save();

            $send_otp_response = json_decode($customer_config->sendOtp());

            if ($send_otp_response->status == 'SUCCESS') {
                //       echo "here21";
                // exit;
                \Drupal::configFactory()->getEditable('miniorange_saml_idp.settings')->set('miniorange_saml_tx_id', $send_otp_response->txId)->save();
                $current_status = 'VALIDATE_OTP';
                \Drupal::configFactory()->getEditable('miniorange_saml_idp.settings')->set('miniorange_saml_status', $current_status)->save();
                drupal_set_message(t('Verify email address by entering the passcode sent to @username', [
                    '@username' => $username
                ]));
            }
        } elseif ($check_customer_response->status == 'CURL_ERROR') {
            //      echo "here3";
            // exit;
            drupal_set_message(t('cURL is not enabled. Please enable cURL'), 'error');
        } else {
// echo "here4";
// exit;

            $customer_keys_response = json_decode($customer_config->getCustomerKeys());

            if (json_last_error() == JSON_ERROR_NONE) {
                \Drupal::configFactory()->getEditable('miniorange_saml_idp.settings')->set('miniorange_saml_customer_id', $customer_keys_response->id)->save();
                \Drupal::configFactory()->getEditable('miniorange_saml_idp.settings')->set('miniorange_saml_customer_admin_token', $customer_keys_response->token)->save();
                \Drupal::configFactory()->getEditable('miniorange_saml_idp.settings')->set('miniorange_saml_customer_admin_email', $username)->save();
                \Drupal::configFactory()->getEditable('miniorange_saml_idp.settings')->set('miniorange_saml_customer_admin_phone', $phone)->save();
                \Drupal::configFactory()->getEditable('miniorange_saml_idp.settings')->set('miniorange_saml_customer_api_key', $customer_keys_response->apiKey)->save();
                $current_status = 'PLUGIN_CONFIGURATION';
                \Drupal::configFactory()->getEditable('miniorange_saml_idp.settings')->set('miniorange_saml_status', $current_status)->save();
                drupal_set_message(t('Successfully retrieved your account.'));
            } else {
                drupal_set_message(t('Invalid credentials'), 'error');
            }

            if (\Drupal::config('miniorange_saml_idp.settings')->get('miniorange_saml_license_key')) {

                $key = \Drupal::config('miniorange_saml_idp.settings')->get('miniorange_saml_customer_admin_token');

                $code = Utilities::decrypt_data(\Drupal::config('miniorange_saml_idp.settings')->get('miniorange_saml_license_key'), $key);
                $username = \Drupal::config('miniorange_saml_idp.settings')->get('miniorange_saml_customer_admin_email');
                $phone = \Drupal::config('miniorange_saml_idp.settings')->get('miniorange_saml_customer_admin_phone');
                $customer = new MiniorangeSAMLCustomer($username, $phone, NULL, NULL);
                $content = json_decode($customer->verifyLicense($code), TRUE);

                if (strcasecmp($content['status'], 'SUCCESS') == 0) {

                    drupal_set_message(t('Your license is verified. You can now setup the plugin.'));
                } else {
                    drupal_set_message(t('License key for this instance is incorrect. Make sure you have not tampered with it at all. Please enter a valid license key.'), 'error');
                    \Drupal::config('miniorange_saml_idp.settings')->clear('miniorange_saml_license_key')->save();
                }
            } else {
                $current_status = 'PLUGIN_CONFIGURATION';
                \Drupal::configFactory()->getEditable('miniorange_saml_idp.settings')->set('miniorange_saml_status', $current_status)->save();
            }
        }
    }

    function miniorange_saml_idp_back(&$form, $form_state) {
        $current_status = 'CUSTOMER_SETUP';
        \Drupal::configFactory()->getEditable('miniorange_saml_idp.settings')->set('miniorange_saml_status', $current_status)->save();
        \Drupal::configFactory()->getEditable('miniorange_saml_idp.settings')->clear('miniorange_miniorange_saml_customer_admin_email')->save();
        \Drupal::configFactory()->getEditable('miniorange_saml_idp.settings')->clear('miniorange_saml_customer_admin_phone')->save();
        \Drupal::configFactory()->getEditable('miniorange_saml_idp.settings')->clear('miniorange_saml_tx_id')->save();

        drupal_set_message(t('Register/Login with your miniOrange Account'), 'status');
    }

    public function miniorange_saml_idp_resend_otp(&$form, $form_state) {

        \Drupal::configFactory()->getEditable('miniorange_saml_idp.settings')->clear('miniorange_saml_tx_id')->save();
        $username = \Drupal::config('miniorange_saml_idp.settings')->get('miniorange_saml_customer_admin_email');
        $phone = \Drupal::config('miniorange_saml_idp.settings')->get('miniorange_saml_customer_admin_phone');
        $customer_config = new MiniorangeSAMLCustomer($username, $phone, NULL, NULL);
        $send_otp_response = json_decode($customer_config->sendOtp());
        if ($send_otp_response->status == 'SUCCESS') {
            // Store txID.
            \Drupal::configFactory()->getEditable('miniorange_saml_idp.settings')->set('miniorange_saml_tx_id', $send_otp_response->txID)->save();
            $current_status = 'VALIDATE_OTP';
            \Drupal::configFactory()->getEditable('miniorange_saml_idp.settings')->set('miniorange_saml_status', $current_status)->save();
            drupal_set_message(t('Verify email address by entering the passcode sent to @username', array('@username' => $username)));
        }
    }

    public function miniorange_saml_idp_validate_otp_submit(&$form, $form_state) {

        $otp_token = $form['miniorange_saml_customer_otp_token']['#value'];
        $username = \Drupal::config('miniorange_saml_idp.settings')->get('miniorange_saml_customer_admin_email');
        $phone = \Drupal::config('miniorange_saml_idp.settings')->get('miniorange_saml_customer_admin_phone');
        $tx_id = \Drupal::config('miniorange_saml_idp.settings')->get('miniorange_saml_tx_id');
        $customer_config = new MiniorangeSAMLCustomer($username, $phone, NULL, $otp_token);
        $validate_otp_response = json_decode($customer_config->validateOtp($tx_id));

        if ($validate_otp_response->status == 'SUCCESS') {

            $current_status = 'PLUGIN_CONFIGURATION';
            \Drupal::configFactory()->getEditable('miniorange_saml_idp.settings')->set('miniorange_saml_status', $current_status)->save();
            \Drupal::configFactory()->getEditable('miniorange_saml_idp.settings')->clear('miniorange_saml_tx_id')->save();

            $password = \Drupal::config('miniorange_saml_idp.settings')->get('miniorange_saml_customer_admin_password');
            $customer_config = new MiniorangeSAMLCustomer($username, $phone, $password, NULL);
            $create_customer_response = json_decode($customer_config->createCustomer());
            if ($create_customer_response->status == 'SUCCESS') {

                $current_status = 'PLUGIN_CONFIGURATION';
                \Drupal::configFactory()->getEditable('miniorange_saml_idp.settings')->set('miniorange_saml_status', $current_status)->save();
                \Drupal::configFactory()->getEditable('miniorange_saml_idp.settings')->set('miniorange_saml_customer_admin_email', $username)->save();
                \Drupal::configFactory()->getEditable('miniorange_saml_idp.settings')->set('miniorange_saml_customer_admin_phone', $phone)->save();
                \Drupal::configFactory()->getEditable('miniorange_saml_idp.settings')->set('miniorange_saml_customer_admin_token', $create_customer_response->token)->save();
                \Drupal::configFactory()->getEditable('miniorange_saml_idp.settings')->set('miniorange_saml_customer_id', $create_customer_response->id)->save();
                \Drupal::configFactory()->getEditable('miniorange_saml_idp.settings')->set('miniorange_saml_customer_api_key', $create_customer_response->apiKey)->save();

                drupal_set_message(t('Customer account created. Please verify your license key to activate plugin'));
                $response = new RedirectResponse('admin/config/people/miniorange_saml_idp/licensing');
                $response->send();
            } else {
                drupal_set_message(t('Error creating customer'), 'error');
            }
        } else {
            drupal_set_message(t('Error validating OTP'), 'error');
        }
    }

    /**
     * Handles License.
     */
    function miniorange_saml_validate_license_submit(&$form, $form_state) {

        $code = trim($form['miniorange_saml_license_key']['#value']);

        $customer = new MiniorangeSAMLCustomer(NULL, NULL, NULL, NULL);
        $response = $customer->check_status($code);

        if (strcasecmp($response['status'], 'SUCCESS') == 0) {
            $content = json_decode($customer->ccl(), true);
            $UserCount = $content['noOfUsers'];


            $l_exp = strtotime($content['licenseExpiry']);


            $key = \Drupal::config('miniorange_saml_idp.settings')->get('miniorange_saml_customer_admin_token');
            $stored_key = Utilities::encrypt_data($code, $key);

            \Drupal::configFactory()->getEditable('miniorange_saml_idp.settings')->set('miniorange_saml_license_key', $stored_key)->save();

            // \Drupal::configFactory()->getEditable('miniorange_saml_idp.settings')->set('miniorange_saml_idp_sml_lk', $stored_key)-> save();

            \Drupal::configFactory()->getEditable('miniorange_saml_idp.settings')->set('miniOrange_saml_idp_user_count', $UserCount)->save();


            \Drupal::configFactory()->getEditable('miniorange_saml_idp.settings')->set('miniOrange_saml_idp_l_exp', $l_exp)->save();

            \Drupal::configFactory()->getEditable('miniorange_saml_idp.settings')->set('tmp_exp', $l_exp)->save();

            \Drupal::configFactory()->getEditable('miniorange_saml_idp.settings')->set('te_count', 0)->save();
            \Drupal::configFactory()->getEditable('miniorange_saml_idp.settings')->set('ue_count', 0)->save();
            \Drupal::configFactory()->getEditable('miniorange_saml_idp.settings')->set('dcheck', 0)->save();


            drupal_set_message(t('Your license has been verified. You can configure your Service Provider settings now.'));
        } else if (strcasecmp($content['status'], 'FAILED') == 0) {
            if (strcasecmp($content['message'], 'Code has Expired') == 0) {
                drupal_set_message(t('License key you have entered has already been used. Please enter a key which has not been used before on any other instance or if you have exausted all your keys thenbuy more license from Licensing'), 'error');
            } else
                drupal_set_message(t('You have entered an invalid license key. Please enter a valid license key.'), 'error');
        }else {
            drupal_set_message(t('An error occured while processing your request. Please Try again.'), 'error');
        }
    }

}
