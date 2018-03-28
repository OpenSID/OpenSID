for f in * ; do mv -- "$f" "$(tr [:lower:] [:upper:] <<< "$f")" ; done

