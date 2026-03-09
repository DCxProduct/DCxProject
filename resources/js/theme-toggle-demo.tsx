import { createRoot } from "react-dom/client"
import { DefaultToggle } from "@/components/ui/demo"

const mountNodes = document.querySelectorAll<HTMLElement>("[data-theme-toggle-root]")

mountNodes.forEach((node) => {
  const root = createRoot(node)
  root.render(<DefaultToggle />)
})
