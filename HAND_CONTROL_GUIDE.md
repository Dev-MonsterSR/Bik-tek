# Guía de Control por Gestos con las Manos 🤚

## Descripción

Bik-tek ahora incluye un sistema de control por gestos utilizando **MediaPipe Hands** con el modelo pesado (heavy model) para un seguimiento preciso de las manos. Esta característica futurista permite controlar la interfaz web usando solo tus manos y una cámara web.

## Cómo Activar

1. Haz clic en el botón flotante azul con el ícono de una mano (📍) ubicado en la esquina inferior izquierda de la pantalla.
2. Permite el acceso a tu cámara web cuando el navegador lo solicite.
3. El botón se pondrá verde cuando el control esté activo.
4. Aparecerá una ventana pequeña en la esquina inferior derecha mostrando la vista de tu cámara con las marcas de seguimiento de la mano.

## Gestos Disponibles

### 👆 Apuntar (Pointing)
**Cómo hacerlo:** Extiende solo tu dedo índice mientras mantienes los otros dedos cerrados.

**Función:** 
- Mueve un cursor virtual en la pantalla siguiendo tu dedo índice.
- Mantén el gesto durante 1 segundo sobre un elemento para hacer clic automáticamente.

**Indicador visual:** El cursor se agranda y cambia a azul oscuro cuando detecta el gesto.

### ✋ Palma Abierta (Open Palm)
**Cómo hacerlo:** Extiende todos tus dedos (mano abierta).

**Función:** Pausa momentánea del sistema de control.

**Indicador visual:** El cursor cambia a color amarillo y aparece el mensaje "⏸️ Pausa".

### ⬆️ Deslizar Arriba (Swipe Up)
**Cómo hacerlo:** Mueve tu mano hacia arriba con el dedo índice extendido.

**Función:** Desplaza la página hacia arriba.

**Indicador visual:** Aparece el mensaje "⬆️ Scroll Up" en el centro de la pantalla.

### ⬇️ Deslizar Abajo (Swipe Down)
**Cómo hacerlo:** Mueve tu mano hacia abajo con el dedo índice extendido.

**Función:** Desplaza la página hacia abajo.

**Indicador visual:** Aparece el mensaje "⬇️ Scroll Down" en el centro de la pantalla.

## Características Técnicas

- **Modelo:** MediaPipe Hands Heavy Model (modelComplexity: 2)
- **Precisión:** Alta confiabilidad en la detección y seguimiento (0.7)
- **Seguimiento:** Detecta hasta 1 mano simultáneamente
- **Latencia:** Procesamiento en tiempo real con retroalimentación visual inmediata

## Requisitos

- Navegador web moderno compatible (Chrome, Edge, Firefox, Safari)
- Cámara web funcional
- Buena iluminación para un mejor seguimiento
- Permisos de cámara activados para el sitio

## Consejos para Mejor Uso

1. **Iluminación:** Usa el control en un ambiente bien iluminado para mejor detección.
2. **Distancia:** Mantén tu mano a una distancia de 30-60 cm de la cámara.
3. **Fondo:** Un fondo uniforme ayuda a mejorar el seguimiento.
4. **Gestos Claros:** Haz gestos deliberados y mantén la posición por un momento.
5. **Calibración:** Los primeros segundos pueden tardar en calibrar; sé paciente.

## Desactivar el Control

Para desactivar el control por gestos:
1. Haz clic nuevamente en el botón flotante (ahora verde).
2. El botón volverá a color azul y la ventana de la cámara desaparecerá.

## Privacidad

- Todo el procesamiento se realiza localmente en tu navegador.
- No se graban ni almacenan imágenes de tu cámara.
- El acceso a la cámara se detiene completamente cuando desactivas el control.

## Seguridad

- El sistema incluye protecciones para evitar clics accidentales en elementos sensibles como botones de envío de formularios.
- Los enlaces externos requieren el atributo `data-gesture-safe` para ser activados por gestos.
- Para entornos de producción, se recomienda alojar las librerías de MediaPipe localmente en lugar de usar CDN.

## Resolución de Problemas

**El control no inicia:**
- Verifica que hayas dado permisos de cámara.
- Recarga la página e intenta nuevamente.
- Verifica que tu cámara funcione en otras aplicaciones.

**El seguimiento es impreciso:**
- Mejora la iluminación de tu entorno.
- Asegúrate de que tu mano esté completamente visible en la cámara.
- Limpia el lente de tu cámara.

**Los gestos no se detectan:**
- Haz gestos más deliberados y claros.
- Mantén el gesto durante al menos 1 segundo para clics.
- Asegúrate de que solo una mano esté visible en la cámara.

## Soporte de Navegadores

✅ Google Chrome 90+
✅ Microsoft Edge 90+
✅ Firefox 88+
✅ Safari 14+

---

**Disfruta de una experiencia futurista controlando Bik-tek con tus manos!** 🚀
