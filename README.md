##LionixCRM
LionixCRM - Open source CRM for SMEs - CRM de código abierto para las PyMEs - http://www.lionix.com/crm

We are basing our work on SuiteCRM, so, in these first steps, plenty of documentation will overlap with theirs.

Estamos basando nuestro trabajo en SuiteCRM, por lo que, en estos primeros pasos, un montón de documentación se solapará con la de ellos.

##MiPyMEs

También atendemos a las MiPyMEs (acrónimo de "micro, pequeña y mediana empresa"), que es una expansión del término original, en donde se incluye a la microempresa.

##SuiteCRM 7.9.1

[![Build Status](https://travis-ci.org/salesagility/SuiteCRM.svg?branch=master)](https://travis-ci.org/salesagility/SuiteCRM)


### What's in this repository ###

This is the git repository for the SuiteCRM project, the award-winning, enterprise-class open source CRM.

This repository has been created to allow community members to collaborate and contribute to the project, helping to develop the SuiteCRM ecosystem.

### Contributing to the project ###

#### Security ####

We take Security seriously here at SuiteCRM so if you have discovered a security risk report it by
emailing security@suitecrm.com. This will be delivered to the product team who handle security issues.
Please don't disclose security bugs publicly until they have been handled by the security team.

Your email will be acknowledged within 24 hours, and you’ll receive a more
detailed response to your email within 72 hours indicating the next steps in
handling your report.

##### Important: Please read before developing code intended for inclusion in the SuiteCRM project. #####

Please read and sign the following [contributor agreement][cont_agrmt]

[cont_agrmt]: https://www.clahub.com/agreements/salesagility/SuiteCRM

The Contributor Agreement only needs to be signed once for all pull requests and contributions.

Once signed and confirmed, any pull requests will be considered for inclusion in the SuiteCRM project.

### Helpful links for the community ###

The following links offer various ways to view, contribute and collaborate to the SuiteCRM project:


+ [SuiteCRM Demo - A fully working SuiteCRM demo available for people to try before downloading the full SuiteCRM package][suitecrm_demo]
+ [SuiteCRM Forums - Forums dedicated to discussions about SuiteCRM with various topics and subjects about SuiteCRM][suitecrm_forums]
+ [SuiteCRM Documentation - A wiki containing relevant documentation to SuiteCRM, constantly being added to][suitecrm_docs]
+ [SuiteCRM Partners - Our partner section where partners of SuiteCRM can be viewed][suitecrm_partners]
+ [SuiteCRM Extensions Directory - An extensions directory where community members can submit extensions built for SuiteCRM][suitecrm_ext]

[suitecrm_demo]: https://suitecrm.com/demo
[suitecrm_forums]: https://suitecrm.com/forum/index
[suitecrm_docs]: https://suitecrm.com/wiki
[suitecrm_partners]: https://suitecrm.com/community/partners
[suitecrm_ext]: https://store.suitecrm.com/

### Development Roadmap ###

[ View the Roadmap here and get involved][suitecrm_roadmap]

[suitecrm_roadmap]: https://suitecrm.com/community/roadmap

### Support & Licensing ###

SuiteCRM is an open source project. As such please do not contact us directly via email or phone for SuiteCRM support. Instead please use our support forum. By using the forum the knowledge is shared with everyone in the community. Our developers answer questions on the forum daily but it also gives the other members of the community the opportunity to contribute. If you would like customisations to specifically fit your SuiteCRM  needs then please use our contact form.

SuiteCRM is published under the AGPLv3 license.


### Descripción Cédulas Personas Costa Rica ###
RegExp: ^[1-9]{1}\d{8}$
Persona Física Nacional (Cédula de Identidad)
Posiciones deben cumplir con la siguiente codificación:
0P-TTTT-AAAA
0 En Hacienda las demás posiciones deben cumplir con la siguiente codificación:
Donde la P representa la provincia, TTTT representa el Tomo justificado con ceros a la izquierda, y AAAA el asiento, que al igual que el tomo, debe estar justificado con ceros a la izquierda.

Persona Física Residente (Cédula de Residencia)
1 En Hacienda las demás posiciones deben cumplir con la siguiente codificación:
1NNN-CC...C-EE...E
Donde NNN representa el código del país, manejado por la Dirección General de Migración y Extranjería, CC...C es el consecutivo de la cantidad total de cédulas de residencia entregas sin importar la nacionalidad y EE...E es un consecutivo de la cantidad de cédulas de residencia entregadas a personas con la misma nacionalidad.

Para los extranjeros que se participaron en el proceso de amnistía, este formato debe cumplir con la siguiente codificación:
1OOO-RE-CC...C-NN-AAAA
Donde OOO es el Código de la oficina regional de la Dirección General de Migración y Extranjería. RE es una constante alfanumérica en mayúsculas, CC...C es un número de consecutivo igual a la posición del solicitante en la lista de cédulas entregadas y cuya longitud depende del número asignado en un momento dado, NN es el núcleo familiar, y AAAA representa el año en que fue vigente la amnistía, actualmente este valor AAAA es constante 1999.

### Descripción Cédulas Jurídicas Costa Rica ###
RegExp: ^(2[1-4]00\d{6}|3[01]\d{2}\d{6}|4000\d{6})$

Link para búsqueda de cédulas jurídicas: http://196.40.56.20/consultasic/wf_consultajuridicas.aspx

Gobierno Central
Este tipo de persona tendrá 2 como primera posición de la cédula.
Las restantes nueve posiciones deben cumplir con la siguiente codificación:
2-PPP-CCCCCC
PPP identifica el Poder, de la siguiente manera:
Código Poder
100    Ejecutivo
200    Legislativo
300    Judicial
400    Tribunal Supremo de Elecciones
Por ejemplo, el número de cédula para el Ministerio de Hacienda es 2100042005

Persona Jurídica
Este tipo de persona tendrá 3 como primera posición de la cédula, de acuerdo con la tabla de naturalezas antes descrita.  Las restantes 9 posiciones deben cumplir con la siguiente codificación:
3-TTT-CCCCCC
Donde TTT representa el Tipo de Persona Jurídica según la Codificación del Registro Nacional, y CCCCCC corresponde a un consecutivo asignado por el Registro Nacional.

Institución Autónoma
Este tipo de persona tendrá un 4 como primera posición de la cédula. Las restantes nueve posiciones deben cumplir con la siguiente codificación:
4-000-CCCCCC
Donde CCCCCC representa un número de consecutivo asignado por el Registro Nacional. Por ejemplo la cédula del Instituto Costarricense de Turismo (ICT) es 4000042141

TTT representa el Tipo de Persona Jurídica según la Codificación del Registro Nacional:
TIPO NOMBRE
000  Instituciones autónomas
100  Poder Ejecutivo
200  Poder Legislativo
300  Poder Judicial
400  Tribunal Supremo de Elecciones
002  Asociaciones
003  Organismos Internacionales
004  Cooperativas
005  Embajadas
006  Fundaciones
007  Personas Jurídicas creadas por Ley Especial
008  Juntas Administrativas de Educación
009  Mutuales de Ahorro y Préstamo
010  Temporalidades de la Iglesia Católica
011  Sindicatos - Uniones de Trabajadores
012  Casas Extranjeras - Poderes
013  Casas Extranjeras - Sin Fines de lucro
014  Municipalidades
101  Sociedades Anónimas
102  Sociedades de Responsabilidad Limitada
103  Sociedades en Comandita
104  Sociedades en Nombre Colectivo
105  Empresas Individuales de Responsabilidad Limitada
106  Sociedades Civiles
107  Sociedades de Usuarios
108  Sociedades de Actividades Profesionales
109  Condominios
110  Otros - Generalmente Fideicomisos
130  Fideicomisos
--última línea
