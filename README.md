## LionixCRM
LionixCRM - Open source CRM for SMEs - CRM de código abierto para las PyMEs - http://www.lionix.com/crm

We are basing our work on SuiteCRM, so, in these first steps, plenty of documentation will overlap with theirs.

Estamos basando nuestro trabajo en SuiteCRM, por lo que, en estos primeros pasos, un montón de documentación se solapará con la de ellos.

## MiPyMEs

También atendemos a las MiPyMEs (acrónimo de "micro, pequeña y mediana empresa"), que es una expansión del término original, en donde se incluye a la microempresa.

<a href="https://suitecrm.com">
  <img width="180px" height="41px" src="https://suitecrm.com/wp-content/uploads/2017/12/logo.png" align="right" />
</a>

# SuiteCRM 7.11.13

[![Build Status](https://travis-ci.org/salesagility/SuiteCRM.svg?branch=hotfix)](https://travis-ci.org/salesagility/SuiteCRM)
[![codecov](https://codecov.io/gh/salesagility/SuiteCRM/branch/hotfix/graph/badge.svg)](https://codecov.io/gh/salesagility/SuiteCRM/branch/hotfix)
[![Gitter chat](https://badges.gitter.im/gitterHQ/gitter.png)](https://gitter.im/suitecrm/Lobby)
[![LICENSE](https://img.shields.io/github/license/suitecrm/suitecrm.svg)](https://github.com/salesagility/suitecrm/blob/hotfix/LICENSE.txt)
[![GitHub contributors](https://img.shields.io/github/contributors/salesagility/suitecrm)](https://github.com/salesagility/SuiteCRM/graphs/contributors)
[![Twitter](https://img.shields.io/twitter/follow/suitecrm.svg?style=social&label=Follow)](https://twitter.com/intent/follow?screen_name=suitecrm)

[Website](https://suitecrm.com) |
[Demo](https://suitecrm.com/demo/) |
[Maintainers](https://salesagility.com) |
[Contributors](https://github.com/salesagility/SuiteCRM/graphs/contributors) |
[Community & Forum](https://suitecrm.com/suitecrm/forum) |
[Partners](https://suitecrm.com/about/about-us/partners/) |
[Extensions Directory](https://store.suitecrm.com/) |
[Translations](https://crowdin.com/project/suitecrmtranslations) | [Code of Conduct](https://docs.suitecrm.com/community/code-of-conduct/)

[SuiteCRM](https://suitecrm.com) is the award-winning open-source, enterprise-ready Customer Relationship Management (CRM) software application.

Our vision is to be the most adopted open source enterprise CRM in the world, giving users full control of their data and freedom to own and customise their business solution.

Try out a free fully working [SuiteCRM demo available here](https://suitecrm.com/demo/)

### Contribute [![contributions welcome](https://img.shields.io/badge/contributions-welcome-brightgreen.svg?style=flat)](https://github.com/salesagility/SuiteCRM/issues)

There are lots of ways to [contribute](https://docs.suitecrm.com/community/) to SuiteCRM

* [Submit bug](https://docs.suitecrm.com/community/raising-issues/) reports and help us [verify fixes](https://docs.suitecrm.com/community/contributing-code/test-pull-requests/) as they are pushed up
* Review and collaborate [source code](https://github.com/salesagility/SuiteCRM/pulls) changes
* Join and engage with other SuiteCRM users and developers on the [forums](https://suitecrm.com/suitecrm/forum)
* [Contribute bug fixes](https://docs.suitecrm.com/community/contributing-code/bugs/)
* Help [translate](https://docs.suitecrm.com/community/contributing-to-docs/contributing-to-translation/) language packs
* [Write and improve](https://docs.suitecrm.com/community/contributing-to-docs/) SuiteCRM documentation
* [Signing CLA](https://www.clahub.com/agreements/salesagility/SuiteCRM) - Only needs to be done once for all PRs and contributions.


### Code Contributors

This project exists thanks to all the people who [contribute](https://github.com/salesagility/SuiteCRM/graphs/contributors) and more.
<a href="https://github.com/salesagility/SuiteCRM/graphs/contributors"><img src="https://opencollective.com/SuiteCRM/contributors.svg?avatarHeight=36&width=890&button=false" /></a>

You wanna buy the **core team** a coffee :coffee: or beer :beer:?
Then consider a small [donation](https://opencollective.com/SuiteCRM/contribute) to help fuel our activities :heart:

### Security ###

We take security seriously here at SuiteCRM so if you have discovered a security risk report it by
emailing [security@suitecrm.com](mailto:security@suitecrm.com). This will be delivered to the product team who handle security issues.
Please don't disclose security bugs publicly until they have been handled by the security team.

Your email will be acknowledged within 24 hours during the business week (Mon - Fri), and you’ll receive a more
detailed response to your email within 72 hours during the business week (Mon - Fri) indicating the next steps in
handling your report.

### Roadmap ###

View the [Roadmap](https://suitecrm.com/roadmap/) and [LTS](https://suitecrm.com/lts/) for details on our planned features and future direction.

### Support ###

SuiteCRM is an open-source project. If you require help with support then please use our [support forum](https://suitecrm.com/suitecrm/forum/). By using the forums the knowledge is shared with everyone in the community. Our developer and community team members answer questions on the forum daily but it also allows the other members of the community to contribute. If you would like customisations to specifically fit your SuiteCRM needs then please visit the [website](https://suitecrm.com/).

### License [![AGPLv3](https://img.shields.io/github/license/suitecrm/suitecrm.svg)](./LICENSE.txt)

SuiteCRM is published under the AGPLv3 license.

### Moneda por defecto Colones Agosto 2017###
Tipo cambio = 580 | conversion_rate = 1/580

`INSERT INTO currencies
(id, name, symbol, iso4217, conversion_rate, status, deleted, date_entered, date_modified, created_by)
VALUES('1', 'Dólar', '$', 'USD', 0.00172413793103448276, 'Active', 0, utc_timestamp(), utc_timestamp(), '1')
;`

### Moneda por defecto Dólares Agosto 2017###
Tipo cambio = 580 | conversion_rate = 1*580
`INSERT INTO currencies
(id, name, symbol, iso4217, conversion_rate, status, deleted, date_entered, date_modified, created_by)
VALUES('2', 'Colones', '₡', 'CRC', 580, 'Active', 0, utc_timestamp, utc_timestamp, '1')
;`

### Descripción Cédulas Personas Costa Rica ###
RegExp: ^[1-9]{1}\d{8}$

Link hacienda: http://www.hacienda.go.cr/consultapagos/ayuda_cedulas.htm

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

Se registran todas las personas mayores de 18 años que tengan su documento de identificación vigente. En el caso de extranjeros residentes en el país con obligaciones ante la Administración Tributaria, deben registrarse con el DIMEX; en caso de no tenerlo, requiere de un NITE (Número de Identificación Tributario Especial), que debe solicitarse en la administración tributaria más cercana. Se exceptúa de la obligatoriedad del uso de ATV a los obligados tributarios que declaran por medio del portal de Tributación Digital, Resolución DGT-R-33-2015 las ocho horas del veintidós de setiembre del dos mil quince, publicado en La Gaceta N°161 del 1 octubre 2015.

### Descripción DIMEX Costa Rica Documento de Identidad Migratoria para Extranjeros ###
RegExp: ^\d{11,12}$

### Descripción NITE Número Identificación Tributaria Especial ###
RegExp: ^\d{12}$

### Descripción Cédulas Jurídicas Costa Rica ###
RegExp: ^(2[1-4]00\d{6}|3[01]\d{2}\d{6}|4000\d{6})$

Link para búsqueda de cédulas jurídicas: http://196.40.56.20/consultasic/wf_consultajuridicas.aspx
Link hacienda: http://www.hacienda.go.cr/consultapagos/ayuda_cedulas.htm

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
